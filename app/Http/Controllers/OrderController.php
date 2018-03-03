<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Order_logic;
use App\logic\Option_logic;
use App\logic\Product_logic;
use App\logic\Upload_logic;
use URL;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $order_extra_column = Order_logic::get_order_extra_column();

        $not_show_on_page = Order_logic::get_not_show_on_page();

        $select_option = Option_logic::get_select_option( $order_extra_column, $not_show_on_page );

        $order_data = Order_logic::get_order_data( $_GET );

        $order_list = Order_logic::get_order_list( $order_data, $select_option );

        $htmlData = Order_logic::order_list_data_bind( $order_list );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "order/order_list";

        $data = compact('assign_page', 'order_data', 'order_list', 'htmlJsonData');

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

        $site = URL::to('/');

        $OriData = Session::get( 'OriData' );

        Session::forget( 'OriData' );

        $htmlData = Order_logic::get_order_input_template_array();
  
        $htmlData = Order_logic::order_input_data_bind( $htmlData, $OriData );

        $htmlData["action"] = "/order/";

        $htmlData["method"] = "post";

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "order/order_input";

        $data = compact('assign_page', 'site', 'htmlJsonData');

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

        $order_extra_column = Order_logic::get_order_extra_column();

        if ( isset($_POST["product_name"]) ) 
        {

            $data = Order_logic::insert_format( $_POST );

            $order_id = Order_logic::add_order( $data );

            $extra_data = Order_logic::insert_extra_format( $_POST, $order_extra_column, $order_id );

            Order_logic::add_extra_order( $extra_data );

        }

        return redirect("/order");

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {

        $site = URL::to('/');

        $order = Order_logic::get_single_order_data( (int)$id );  

        $order = isset($order[0]) && is_object($order[0]) ? get_object_vars($order[0]) : array();  


        $htmlData = Order_logic::get_order_input_template_array();
  
        $htmlData = Order_logic::order_input_data_bind( $htmlData, $order );

        $htmlData["action"] = "/order/" . (int)$id;

        $htmlData["method"] = "patch";

        $htmlJsonData = json_encode($htmlData);


        $assign_page = "order/order_input";

        $data = compact('assign_page', 'htmlJsonData', 'site');

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

        $order_extra_column = Order_logic::get_order_extra_column();

        $order_id = intval($id);

        $data = Order_logic::update_format( $_POST );
        
        Order_logic::edit_order( $data, $order_id );

        $data = Order_logic::update_extra_format( $_POST, $order_extra_column);

        Order_logic::edit_order_extra_data( $data, $order_id ); 

        return redirect("/order");

    }

    public function verify()
    {

        if ( !empty($_POST["order_id"]) ) 
        {

            Order_logic::order_verify( $_POST["order_id"] );

        }

        return redirect("/order");
        
    }

    public function output()
    {

        $not_show_on_page = Order_logic::get_not_show_on_page();

        $order_extra_column = Order_logic::get_order_extra_column();

        $select_option = Option_logic::get_select_option( $order_extra_column, $not_show_on_page );

        $order_data = Order_logic::get_order_data( $_GET );

        $order_list = Order_logic::get_order_list( $order_data, $select_option );

        $htmlData = Order_logic::order_list_data_bind( $order_list );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "order/order_output";

        $data = compact('assign_page', 'order_data', 'order_list', 'htmlJsonData');

        return view('webbase/content', $data);
        
    }

    public function order_output_process()
    {

        $order_id = isset($_POST["order_id"]) && !empty($_POST["order_id"]) ? array_filter($_POST["order_id"], "intval") : array() ;

        $data = Upload_logic::order_output( $order_id );
        
        dd($data);

    }

}
