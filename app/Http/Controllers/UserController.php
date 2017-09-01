<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\logic\Admin_user_logic;
use App\logic\Role_logic;
use App\logic\Service_logic;
use App\logic\Basetool;
use App\logic\Redis_tool;
use Illuminate\Support\Facades\Session;
use App\logic\Register;
use Illuminate\Support\Facades\Storage;

class UserController extends Basetool
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // get now page

        Service_logic::get_service_id_by_url_and_save( $request->path() );

        // search bar setting

        $search_tool = array(2,3,4,5);

        Redis_tool::set_search_tool( $search_tool );

        $Login_user = Session::get('Login_user');

        $data = $_GET;

        $data["parents_id"] = isset($Login_user["user_id"]) ? $Login_user["user_id"] : 0 ;

        $user = Admin_user_logic::get_user_list( $data );

        $user_role = Admin_user_logic::get_user_role();

        $user = Admin_user_logic::get_user_role_auth( $user, $user_role );
 
        $assign_page = "admin_user/admin_list";

        $data = compact('user', 'assign_page', 'service_id');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $_this = new self();

        // 計算子帳數量

        $Login_user = Session::get('Login_user');

        $account_status = Admin_user_logic::cnt_child_account();

        $user = Admin_user_logic::get_user();

        $role_list = Role_logic::get_active_role();

        $role_list = Role_logic::filter_admin_role($role_list);

        $assign_page = "admin_user/admin_input";

        $ErrorMsg = Session::get('ErrorMsg');

        $data = compact('user', 'role_list', 'assign_page', 'ErrorMsg', 'account_status');

        return view('webbase/content', $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if (!empty($_POST["user_id"])) 
        {
            
            // user

            $data = Admin_user_logic::update_format( $_POST );

            $user_id = intval($_POST["user_id"]);

            Admin_user_logic::edit_user( $data, $user_id );

            // user role delete add

            Admin_user_logic::delete_user_role( $user_id );

            Redis_tool::del_user_role( $user_id );

            $data = Admin_user_logic::add_user_role_format( $user_id, $_POST["auth"] );

            Admin_user_logic::add_user_role( $data );

        }
        else
        {

            // parents_id

            $Login_user = Session::get('Login_user');

            $_POST["parents_id"] = isset($Login_user["user_id"]) ? $Login_user["user_id"] : 0 ;
            
            $_POST["social_register"] = 0;


            // verify process

            $ErrorMsg = Register::register_process($_POST);
         
            if (empty($ErrorMsg)) 
            {
    
                // user

                $data = Admin_user_logic::insert_format( $_POST );

                $user_id = Admin_user_logic::add_user( $data );

                // user role add

                $data = Admin_user_logic::add_user_role_format( $user_id, $_POST["auth"] );

                Admin_user_logic::add_user_role( $data );

            }

            if (!empty($ErrorMsg)) 
            {

                Session::put('ErrorMsg', $ErrorMsg);

                return back();

            }


        }

        return redirect("/user");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {

        $_this = new self;

        $account_status = "";

        $user = Admin_user_logic::get_user( $id );

        $user_role = $id > 0 ? Admin_user_logic::get_user_role_by_id( $id ) : "" ;

        $user_role = $_this->get_object_or_array_key( $user_role );

        $role_list = Role_logic::get_active_role();
 
        // $role_list = Role_logic::filter_admin_role($role_list);
 
        $assign_page = "admin_user/admin_input";

        $data = compact('user', 'role_list', 'user_role', 'assign_page', 'account_status');

        return view('webbase/content', $data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * photo_index
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function photo_index()
    {

        $data = Admin_user_logic::get_user_photo();
 
        $assign_page = "admin_user/photo_index";

        $data = compact('user', 'data', 'assign_page');

        return view('webbase/content', $data);

    }

    /**
     * photo_upload_process
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function photo_upload_process(Request $request)
    {

        // 預覽用

        if ($request->hasFile('photo_upload_preview')) 
        {
            
            Storage::makeDirectory("photo_upload_preview");

            $photo_upload_files = $request->file('photo_upload_preview')->store('photo_upload_preview');

            echo json_encode($photo_upload_files);

            exit();
        
        }

        // 存圖

        if ($request->hasFile('photo_upload')) 
        {
            
            Storage::makeDirectory("photo_upload");

            $photo_upload_files = $request->file('photo_upload')->store('photo_upload');

            Admin_user_logic::edit_user_photo( $photo_upload_files );

            echo json_encode($photo_upload_files);

            exit();
        
        }

    }

}
