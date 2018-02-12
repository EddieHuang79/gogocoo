<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Shop_logic;
use App\logic\Mall_logic;
use App\logic\Record_logic;
use App\logic\Ecoupon_logic;
use App\logic\Web_cht;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    /**
     * Service Buy
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $txt = Web_cht::get_txt();

        $JsonTxt = json_encode($txt);

        $shop_product_list = Shop_logic::shop_product_list();

        $htmlJsonData = Shop_logic::get_html_data( $shop_product_list );

        $htmlJsonData = json_encode($htmlJsonData);

        $shop_product_list = $shop_product_list->count() > 0 ? $shop_product_list : "" ;

        $assign_page = "shop/shop_list";

        $data = compact('assign_page', 'shop_product_list', 'JsonTxt', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    /**
     * Service Buy Process
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function shop_buy_process()
    {

        // Record_logic::write_log( "test", json_encode($_POST) );

        $redirectURL = Shop_logic::shop_buy_insert( $_POST );

        return redirect( $redirectURL );

    }

    /**
     * Service Buy Record
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function shop_record()
    {

        $txt = Web_cht::get_txt();

        $JsonTxt = json_encode($txt);        

        $shop_record = Shop_logic::shop_record();

        $htmlData = Shop_logic::get_shop_record_list_template_array();

        $htmlData = Shop_logic::shop_record_list_data_bind( $htmlData, $shop_record["result"] );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "shop/shop_record";

        $data = compact('assign_page', 'shop_record', 'JsonTxt', 'htmlJsonData');

        return view('webbase/content', $data);

    }


    /**
     * get_mall_product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function get_mall_product()
    {

        $store_id = Session::get('Store');

        $data = Shop_logic::get_mall_product( (int)$_POST["mall_product_id"] );

        $data["ecoupon_list"] = Ecoupon_logic::get_user_active_ecoupon( $store_id );

        echo json_encode($data);

        exit();

    }


    /**
     * get_extend_deadline_option 取得期限的選項
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function get_extend_deadline_option()
    {

        // $extend_count = Shop_logic::extend_count();

        $data = Shop_logic::get_extend_deadline_option();

        echo json_encode($data);

        exit();

    }


    public static function DataReceive()
    {

        Record_logic::write_log( "testPayment", json_encode($_POST) ) ;

        Shop_logic::DataReceive( $_POST );

        echo "1|OK";

        exit();

    }

    /**
     * Service Buy Record
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function property_list()
    {

        $txt = Web_cht::get_txt();

        $JsonTxt = json_encode($txt);

        $property_list = Shop_logic::get_property_list();

        $htmlData = Shop_logic::get_property_list_template_array();

        $htmlData = Shop_logic::shop_property_list_data_bind( $htmlData, $property_list );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "shop/property_list";

        $data = compact('assign_page', 'JsonTxt', 'htmlJsonData');

        return view('webbase/content', $data);

    }

}
