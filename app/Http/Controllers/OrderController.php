<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Order_logic;
use App\logic\Option_logic;
use App\logic\Product_logic;
use URL;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $order_extra_column = order_logic::get_order_extra_column();

        $select_option = Option_logic::get_select_option( $order_extra_column );

        $order_data = order_logic::get_order_data( $_GET );

        $order_list = order_logic::get_order_list( $order_data, $select_option );

        $assign_page = "order/order_list";

        $data = compact('assign_page', 'order_data', 'order_list', 'order_extra_column');

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

        $order = "";

        $site = URL::to('/');

        $out_warehouse_category_data = Option_logic::get_out_warehouse_category();

        $has_spec = Product_logic::is_spec_function_active();

        $order_extra_column = order_logic::get_order_extra_column();

        $select_option = Option_logic::get_select_option( $order_extra_column );

        $assign_page = "order/order_input";

        $data = compact('assign_page', 'order', 'out_warehouse_category_data', 'site', 'has_spec', 'order_extra_column', 'select_option');

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

        $order_extra_column = order_logic::get_order_extra_column();


        if (!empty($_POST["order_id"])) 
        {
            
            $order_id = intval($_POST["order_id"]);

            $data = order_logic::update_format( $_POST );
            
            order_logic::edit_order( $data, $order_id );

            $data = order_logic::update_extra_format( $_POST, $order_extra_column);

            order_logic::edit_order_extra_data( $data, $order_id );        

        }
        else
        {

            $data = order_logic::insert_format( $_POST );

            $order_id = order_logic::add_order( $data );

            $extra_data = order_logic::insert_extra_format( $_POST, $order_extra_column, $order_id );

            order_logic::add_extra_order( $extra_data );

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

        $out_warehouse_category_data = Option_logic::get_out_warehouse_category();

        $has_spec = Product_logic::is_spec_function_active();

        $order = order_logic::get_single_order_data( $id );  

        $order = get_object_vars($order[0]);  

        $order_extra_column = order_logic::get_order_extra_column();

        $select_option = Option_logic::get_select_option( $order_extra_column );

        $assign_page = "order/order_input";

        $data = compact('assign_page', 'order', 'out_warehouse_category_data', 'site', 'has_spec', 'order_extra_column', 'select_option');

        return view('webbase/content', $data);

    }

    public function verify()
    {

        if ( !empty($_POST["order_id"]) ) 
        {

            order_logic::order_verify( $_POST["order_id"] );

        }

        return redirect("/order");
        
    }

}
