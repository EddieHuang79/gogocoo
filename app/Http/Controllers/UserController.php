<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\logic\Admin_user_logic;
use App\logic\Role_logic;
use App\logic\Service_logic;
use App\logic\Shop_logic;
use App\logic\Web_cht;
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

        $_this = new self();

        $service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0 ;

        // get now page

        Service_logic::get_service_id_by_url_and_save( $request->path() );

        // search bar setting

        $search_tool = array(2,3,4,5);

        $search_query = $_this->get_search_query( $search_tool, $_GET );

        if ( $service_id > 0 ) 
        {
        
            Redis_tool::set_search_tool( $search_tool, $service_id );
        
        }

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

        $txt = Web_cht::get_txt();

        $Login_user = Session::get('Login_user');

        // 計算子帳數量

        $account_status = Admin_user_logic::cnt_child_account();

        $user = Admin_user_logic::get_user();

        $role_list = Role_logic::get_active_role();

        $role_list = Role_logic::filter_admin_role($role_list);

        $buy_spec_data = array();

        if ( !empty($account_status["buy_spec_data"]) ) 
        {

            foreach ($account_status["buy_spec_data"] as $index => $spec_data) 
            {

                $buy_spec_data[$index] = $spec_data.$txt["buy_date_spec_desc"].date("Y-m-d", strtotime("+".$spec_data." days"));

            }

        }

        $deadline = !empty($account_status['free']) ? date("Y-m-d", strtotime("+30 days")) : $buy_spec_data ;

        $assign_page = "admin_user/admin_input";

        $ErrorMsg = Session::get('ErrorMsg');

        $OriData = Session::get( 'OriData' );

        Session::forget('ErrorMsg');

        Session::forget('OriData');

        $data = compact('user', 'role_list', 'assign_page', 'ErrorMsg', 'account_status', 'deadline', 'OriData');

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
        
        $_this = new self();

        $txt = Web_cht::get_txt();

        // parents_id

        $Login_user = Session::get('Login_user');

        $_POST["parents_id"] = isset($Login_user["user_id"]) ? $Login_user["user_id"] : 0 ;
        
        $_POST["social_register"] = 0;


        try
        {

            // verify process

            $ErrorMsg = Admin_user_logic::account_verify( $_POST );

            if (!empty($ErrorMsg)) 
            {

                throw new \Exception( json_encode($ErrorMsg) );

            }

            // user

            $data = Admin_user_logic::insert_format( $_POST );

            $user_id = Admin_user_logic::add_user( $data );

            // user role add

            $data = Admin_user_logic::add_user_role_format( $user_id, $_POST["auth"] );

            Admin_user_logic::add_user_role( $data );

            $date_spec = isset($_POST["date_spec"]) && !empty($_POST["date_spec"]) ? trim($_POST["date_spec"]) : "" ;

            // 紀錄價值服務啟用列表，免費不紀錄

            if ( !empty($date_spec) ) 
            {

                Shop_logic::add_use_record( (int)$user_id, $date_spec, $type = 1 );

            }

        }
        catch(\Exception $e)
        {

            Session::put( 'OriData', $_POST );

            Session::put( 'ErrorMsg', $_this->show_error_to_user( json_decode($e->getMessage() ,true) ) );

            return back();

        }

        $page_query = $_this->made_search_query();

        return redirect("/user".$page_query);

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

        $user = Admin_user_logic::get_user( (int)$id );

        $user_role = $id > 0 ? Admin_user_logic::get_user_role_by_id( (int)$id ) : "" ;

        $user_role = $_this->get_object_or_array_key( $user_role );

        $role_list = Role_logic::get_active_role();

        $role_list = Role_logic::filter_admin_role($role_list);

        $ErrorMsg = Session::get('ErrorMsg');

        Session::forget('ErrorMsg');

        // $role_list = Role_logic::filter_admin_role($role_list);
 
        $assign_page = "admin_user/admin_input";

        $data = compact('user', 'role_list', 'user_role', 'assign_page', 'account_status', 'ErrorMsg');

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


        $_this = new self();

        $txt = Web_cht::get_txt();
            
        try
        {

            $ErrorMsg = Admin_user_logic::account_verify( $_POST );

            if (!empty($ErrorMsg)) 
            {

                throw new \Exception( json_encode($ErrorMsg) );

            }

            // user

            $data = Admin_user_logic::update_format( $_POST );

            $user_id = intval($id);

            Admin_user_logic::edit_user( $data, $user_id );

            // user role delete add

            Admin_user_logic::delete_user_role( $user_id );

            Redis_tool::del_user_role( $user_id );

            $data = Admin_user_logic::add_user_role_format( $user_id, $_POST["auth"] );

            Admin_user_logic::add_user_role( $data );


        }
        catch(\Exception $e)
        {

            Session::put( 'ErrorMsg', $_this->show_error_to_user( json_decode($e->getMessage() ,true) ) );

            return back();

        }


        $page_query = $_this->made_search_query();

        return redirect("/user".$page_query);

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

    /**
     * extend_user_process 擴展帳號期限
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function extend_user_process(Request $request)
    {

        $user_id = isset($_POST["user_id"]) ? intval($_POST["user_id"]) : 0 ;

        $date_spec = isset($_POST["date_spec"]) ? trim($_POST["date_spec"]) : "" ;

        $result = Admin_user_logic::extend_user_deadline( $user_id, $date_spec );

        echo json_encode($result);

        exit();

    }

}
