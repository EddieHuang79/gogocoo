<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Purchase_logic;
use App\logic\Option_logic;
use App\logic\Product_logic;
use App\logic\Stock_logic;
use URL;

class PurchaseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $purchase_extra_column = Purchase_logic::get_purchase_extra_column();

        $select_option = Option_logic::get_select_option( $purchase_extra_column );

        $purchase_data = Purchase_logic::get_purchase_data( $_GET );

        $purchase_list = Purchase_logic::get_purchase_list( $purchase_data, $select_option );

        $assign_page = "purchase/purchase_list";

        $data = compact('assign_page', 'purchase_data', 'purchase_list', 'purchase_extra_column');

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

        $purchase = "";

        $site = URL::to('/');

        $in_warehouse_category_data = Option_logic::get_in_warehouse_category();

        $has_spec = Product_logic::is_spec_function_active();

        $purchase_extra_column = Purchase_logic::get_purchase_extra_column();

        $select_option = Option_logic::get_select_option( $purchase_extra_column );

        $assign_page = "purchase/purchase_input";

        $data = compact('assign_page', 'purchase', 'in_warehouse_category_data', 'site', 'has_spec', 'purchase_extra_column', 'select_option');

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

        $purchase_extra_column = Purchase_logic::get_purchase_extra_column();

        if (!empty($_POST["purchase_id"])) 
        {
            
            $purchase_id = intval($_POST["purchase_id"]);

            $data = Purchase_logic::update_format( $_POST );
            
            Purchase_logic::edit_purchase( $data, $purchase_id );

            $data = Purchase_logic::update_extra_format( $_POST, $purchase_extra_column);

            Purchase_logic::edit_purchase_extra_data( $data, $purchase_id );        

        }
        else
        {

            $data = Purchase_logic::insert_format( $_POST );

            $purchase_id = Purchase_logic::add_purchase( $data );

            $extra_data = Purchase_logic::insert_extra_format( $_POST, $purchase_extra_column, $purchase_id );

            Purchase_logic::add_extra_purchase( $extra_data );

        }

        return redirect("/purchase");

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

        $in_warehouse_category_data = Option_logic::get_in_warehouse_category();

        $has_spec = Product_logic::is_spec_function_active();

        $purchase = Purchase_logic::get_single_purchase_data( $id );  

        $purchase = get_object_vars($purchase[0]);  

        $purchase["in_warehouse_number"] = str_pad($purchase["in_warehouse_number"], 8, "0", STR_PAD_LEFT);

        $purchase_extra_column = Purchase_logic::get_purchase_extra_column();

        $select_option = Option_logic::get_select_option( $purchase_extra_column );

        $assign_page = "purchase/purchase_input";

        $data = compact('assign_page', 'purchase', 'in_warehouse_category_data', 'site', 'has_spec', 'purchase_extra_column', 'select_option');

        return view('webbase/content', $data);

    }

    public function verify()
    {

        if ( !empty($_POST["purchase_id"]) ) 
        {

            // filter

            $purchase_id_array = array_filter($_POST["purchase_id"], "intval");

            // 取得入庫數量

            $purchase_data = Purchase_logic::get_purchase_stock_data( $purchase_id_array ); 

            // 入庫格式

            $data = Stock_logic::insert_format( $purchase_data );

            // 入庫

            Stock_logic::add_stock( $data );

            // 改狀態為已入帳

            Purchase_logic::purchase_verify( $purchase_id_array ); 

        }

        return redirect("/purchase");
        
    }

}
