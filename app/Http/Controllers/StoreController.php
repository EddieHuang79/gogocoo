<?php

namespace App\Http\Controllers;

use DummyFullModelClass;
use App\lain;
use Illuminate\Http\Request;
use App\logic\Option_logic;
use App\logic\Store_logic;
use App\logic\Msg_logic;
use Illuminate\Support\Facades\Session;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function index(lain $lain)
    {

        // get now page

        // Service_logic::get_service_id_by_url_and_save( $request->path() );

        // search bar setting

        // $search_tool = array(5,6);

        // Redis_tool::set_search_tool( $search_tool );

        $store = store_logic::get_store_info( $_GET );
 
        $assign_page = "store/store_list";

        $data = compact('assign_page', 'store');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function create(lain $lain)
    {

        $store = "";

        $store_type = Option_logic::get_store_data();

        $assign_page = "store/store_input";


        // 檢查店鋪創立狀況

        $store_status = Store_logic::get_store_cnt();


        // 第一間店舖的資訊

        $store_info = Store_logic::get_store_info();

        $store_info = $store_info[0];

        $data = compact('assign_page', 'store', 'store_type', 'store_status', 'store_info');

        return view('webbase/content', $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, lain $lain)
    {


        if (!empty($_POST["store_id"])) 
        {
            
            $data = Store_logic::update_format( $_POST );

            $store_id = intval($_POST["store_id"]);

            Store_logic::edit_store( $data, $store_id );

        }
        else
        {

            $data = Store_logic::insert_format( $_POST );

            // 店鋪代號若為空 隨機產生

            Store_logic::add_store( $data );

        }

        return redirect("/store");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function show(lain $lain, DummyModelClass $DummyModelVariable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {

        $store_status = array("left" => 1);

        $edit = 1;

        $store = Store_logic::get_single_store( $id ); 

        // 第一間店舖的資訊

        $store_info = Store_logic::get_store_info( array("store_id" => $id) );

        $assign_page = "store/store_input";

        $data = compact('assign_page', 'store', 'store_info', 'store_status', 'edit');

        return view('webbase/content', $data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, lain $lain, DummyModelClass $DummyModelVariable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function destroy(lain $lain, DummyModelClass $DummyModelVariable)
    {
        //
    }


    /**
     * Change Store
     */
    public function change_store()
    {

        $check = Store_logic::check_store_user( $_POST["store_id"] );

        if ( $check === true ) 
        {
            Store_logic::change_store( $_POST["store_id"] );
        }
        else
        {
            $subject = "系統通知";
            $content = "所選擇店家不屬於此帳號管理，若有疑問請聯絡管理員！";

            $Login_user = Session::get("Login_user");

            Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );
        }

        return redirect("/index");

    }

}
