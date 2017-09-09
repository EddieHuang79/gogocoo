<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Shop_logic;
use App\logic\Mall_logic;

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

        $shop_product_list = Shop_logic::shop_product_list();

        $shop_product_list = $shop_product_list->count() > 0 ? $shop_product_list : "" ;

        $assign_page = "shop/shop_list";

        $data = compact('assign_page', 'shop_product_list');

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

        // $data = Shop_logic::shop_buy_format( $_POST );

        $result = Shop_logic::shop_buy_insert( $_POST );

        echo json_encode($result);

        exit();

    }

    /**
     * Service Buy Record
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function shop_record()
    {

        $shop_record = Shop_logic::shop_record();

        $assign_page = "shop/shop_record";

        $data = compact('assign_page', 'shop_record');

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

        $data = Shop_logic::get_mall_product( $_POST["mall_product_id"] );

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

}
