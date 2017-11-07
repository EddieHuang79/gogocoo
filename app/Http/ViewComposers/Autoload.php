<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use App\logic\Web_cht;
use App\logic\Login;
use App\logic\Redis_tool;
use App\logic\Admin_user_logic;
use App\logic\Service_logic;
use App\logic\Role_logic;
use App\logic\Basetool;
use App\logic\Msg_logic;
use App\logic\Store_logic;
use App\logic\Shop_logic;

class Autoload extends Basetool
{
    
    public function GetMenu(View $view)
    {

        $_this = new self();
	
        /* get user role as redis key */

        $Login_user = Session::get('Login_user');

        $Role_data = Admin_user_logic::get_user_role_by_id( $Login_user["user_id"] );

        $Role_array = !empty($Role_data) ? $_this->get_object_or_array_key($Role_data) : array();

        $Role_array = json_encode($Role_array);

        /* get user role as redis key */

        $data = Service_logic::menu_list($Role_array);

        $service_list = Service_logic::menu_format($data);

        $icon = $_this->menu_icon();

        $service_id = Session::get('service_id') ? intval(Session::get('service_id')) : 0 ;

        $service_id = isset($_GET["service_id"]) ? intval($_GET["service_id"]) : $service_id ;

        $auth_check = $service_id > 0 ? Service_logic::auth_check($service_id, $service_list) : true;

        $breadcrumb = Service_logic::breadcrumb( $service_id, $service_list );

        !$auth_check ? header("Location: /admin_index") : "" ;

        Session::put('service_id', $service_id);

        $data = compact('service_list', 'service_id', 'breadcrumb', 'icon');

    	$view->with($data);

    }

    public function GetTxt(View $view)
    {
	
        $txt = Web_cht::get_txt();

    	$active_to_text = array(1 => $txt["enable"], 2 => $txt["disable"]);

        $data = compact('txt', 'active_to_text');

    	$view->with($data);

    }

    public function CheckLogin(View $view)
    {
    
        $is_login = Login::is_user_login();

        if ($is_login) 
        {
            $user = Login::get_login_user_data();

            $data = compact('user');

            $view->with($data);
            
        }else
        {
            header("Location: /index");
            exit();
        }

    }

    public function SearchTool(View $view)
    {

        $search_tool = Redis_tool::get_search_tool();

        $active_role_list = Role_logic::get_active_role();

        $data = compact('search_tool', 'active_role_list');

        $view->with($data);

    }

    public function GetMsg(View $view)
    {

        $_this = new self();

        /* get user role as redis key */

        $Login_user = Session::get('Login_user');

        $Role_data = Admin_user_logic::get_user_role_by_id( $Login_user["user_id"] );

        $Role_array = !empty($Role_data) ? $_this->get_object_or_array_key($Role_data) : array();

        $Role_array[] = 0;


        // 一般訊息

        $msg_list = Msg_logic::get_msg( $Role_array, array(1,2), array(1) );

        $msg_cnt = $msg_list->count();

        $msg_list = Msg_logic::time_format( $msg_list );


        // 系統訊息

        $sys_msg_list = Msg_logic::get_msg( $Role_array, array(1,2), array(2) );

        $sys_msg_cnt = $sys_msg_list->count();

        $sys_msg_list = Msg_logic::time_format( $sys_msg_list );


        // popup

        $popup_msg = Msg_logic::get_msg( $Role_array, array(2), array(1,2) );


        // show one msg and add redis

        $popup_msg = Msg_logic::show_popup_msg( $popup_msg );

        $data = compact('msg_list', 'msg_cnt', 'sys_msg_list', 'sys_msg_cnt', 'popup_msg');

        $view->with($data);

    }

    public function GetStore(View $view)
    {

        $store_info = Store_logic::get_store_info();

        $now_store = Session::get( 'Store' );

        $data = compact('store_info', 'now_store');

        $view->with($data);

    }

    public function GetPhoto(View $view)
    {

        $data = Admin_user_logic::get_user_photo();

        $data = compact('data');

        $view->with($data);

    }

    public function is_over_deadline(View $view)
    {

        $over_date_des = array();

        $txt = Web_cht::get_txt();

        $is_over_date = false;

        $Login_user = Session::get('Login_user');

        // 店家過期日

        $now_store = Session::get( 'Store' );

        $cnt_deadline = Shop_logic::count_deadline( array($now_store), "create_shop" );

        $created_data = Store_logic::get_single_store( $now_store );

        $store_deadline = Shop_logic::get_deadline( $created_data, $cnt_deadline );

        if ( strtotime($store_deadline) < time() && $Login_user["user_id"] != 1 ) 
        {

            $is_over_date = true ;

            $over_date_des[] = $txt["store_over_date_desc"];

        }

        // 帳號過期日

        $cnt_deadline = Shop_logic::count_deadline( array($Login_user["user_id"]), "child_account" );

        $created_data = Admin_user_logic::get_user( $Login_user["user_id"] );

        $account_deadline = Shop_logic::get_deadline( $created_data, $cnt_deadline );

        if ( strtotime($account_deadline) < time() && $Login_user["user_id"] != 1 ) 
        {

            $is_over_date = true ;

            $over_date_des[] = $txt["account_over_date_desc"];

        }

        $data = compact('is_over_date', 'over_date_des');

        $view->with($data);

    }

    protected function menu_icon()
    {

        $result = array(
                        "首頁"             =>    'fa-home',
                        "使用者管理"        =>    'fa-user',
                        "角色管理"          =>    'fa-group',
                        "服務管理"          =>    'fa-briefcase',
                        "GO商城"           =>    'fa-shopping-cart',
                        "店鋪管理"          =>    'fa-scribd',
                        "訊息管理"          =>    'fa-comment',
                        "商品管理"          =>    'fa-cube',
                        "商城管理"          =>    'fa-building',
                        "進貨管理"          =>    'fa-cart-plus',
                        "庫存管理"          =>    'fa-bar-chart',
                        "訂單管理"          =>    'fa-clipboard',
                        "資料上傳"          =>    'fa-upload',
                        "商品分類管理"      =>    'fa-pie-chart',
                        "電子報管理"         =>    'fa-envelope',
                    );

        return $result;

    }

}

?>