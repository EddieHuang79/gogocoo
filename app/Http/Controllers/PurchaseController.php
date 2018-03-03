<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Purchase_logic;
use App\logic\Option_logic;
use App\logic\Product_logic;
use App\logic\Stock_logic;
use URL;
use Illuminate\Support\Facades\Session;

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

        $htmlData = Purchase_logic::purchase_list_data_bind( $purchase_list );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "purchase/purchase_list";

        $data = compact('assign_page', 'purchase_data', 'purchase_list', 'htmlJsonData');

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

        $htmlData = Purchase_logic::get_purchase_input_template_array();

        $htmlData = Purchase_logic::purchase_input_data_bind( $htmlData, $OriData );

        $htmlData["action"] = "/purchase";

        $htmlData["method"] = "post"; 
  
        $htmlJsonData = json_encode($htmlData);


        $assign_page = "purchase/purchase_input";

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

        $purchase_extra_column = Purchase_logic::get_purchase_extra_column();

        if ( isset($_POST["product_name"]) ) 
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

        $purchase = Purchase_logic::get_single_purchase_data( (int)$id );  

        $purchase = get_object_vars($purchase[0]);  

        $purchase["in_warehouse_number"] = str_pad($purchase["in_warehouse_number"], 8, "0", STR_PAD_LEFT);


        $htmlData = Purchase_logic::get_purchase_input_template_array();
  
        $htmlData = Purchase_logic::purchase_input_data_bind( $htmlData, $purchase );

        $htmlData["action"] = "/purchase/" . (int)$id;

        $htmlData["method"] = "patch";

        $htmlJsonData = json_encode($htmlData);


        $assign_page = "purchase/purchase_input";

        $data = compact('assign_page', 'site', 'htmlJsonData');

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

        $purchase_extra_column = Purchase_logic::get_purchase_extra_column();

        $purchase_id = intval($id);

        $_POST["purchase_id"] = $purchase_id;

        $data = Purchase_logic::update_format( $_POST );

        Purchase_logic::edit_purchase( $data, $purchase_id );

        $data = Purchase_logic::update_extra_format( $_POST, $purchase_extra_column);

        Purchase_logic::edit_purchase_extra_data( $data, $purchase_id );   

        return redirect("/purchase");

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
