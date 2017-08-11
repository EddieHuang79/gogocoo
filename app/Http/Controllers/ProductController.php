<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Product_logic;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $product_data = Product_logic::get_product_data();

        $product_extra_column = Product_logic::get_product_extra_column(); 

        $product_list = Product_logic::get_product_list( $product_data );

        $assign_page = "product/product_list";

        $data = compact('assign_page', 'product_data', 'product_list', 'product_extra_column');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $product_spec_column = Product_logic::get_product_spec_column(); 

        $has_spec = !empty($product_spec_column) ? 1 : 0 ;

        $product = "";

        $product_spec = ""; 
 
        $assign_page = "product/product_input";

        $product_extra_column = Product_logic::get_product_extra_column(); 

        $data = compact('assign_page', 'product', 'product_spec', 'product_extra_column', 'product_spec_column', 'has_spec');

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

        $has_spec = Product_logic::is_spec_function_active(); 

        $product_extra_column = Product_logic::get_product_extra_column(); 

        if (!empty($_POST["product_id"])) 
        {
            
            $product_id = intval($_POST["product_id"]);

            $data = Product_logic::update_main_format( $_POST );

            Product_logic::edit_product( $data, $product_id );

            $data = Product_logic::update_extra_format( $_POST, $product_extra_column );

            Product_logic::edit_product_extra_data( $data, $product_id );

            if ( $has_spec ) 
            {

                $spec_data = Product_logic::insert_or_update_spec_data( "update", $_POST, $product_id );

                Product_logic::edit_product_spec_data( $spec_data );

                $spec_data = Product_logic::insert_or_update_spec_data( "insert", $_POST, $product_id );

                if ( !empty($spec_data) ) 
                {

                    Product_logic::add_product_spec_data( $spec_data );
                
                }

            }


        }
        else
        {

            $data = Product_logic::insert_main_format( $_POST );

            $product_id = Product_logic::add_product( $data );

            $data = Product_logic::insert_extra_format( $_POST, $product_extra_column, $product_id );

            Product_logic::add_extra_data( $data );

            if ( $has_spec ) 
            {

                $data = Product_logic::insert_or_update_spec_data( "insert", $_POST, $product_id );

                Product_logic::add_product_spec_data( $data );

            }

        }

        return redirect("/product");

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {

        $product = Product_logic::get_single_product( $id );  

        $product = get_object_vars($product);  

        $product_extra_column = Product_logic::get_product_extra_column(); 

        $product_spec_column = Product_logic::get_product_spec_column(); 

        $has_spec = !empty($product_spec_column) ? 1 : 0 ;

        $product_spec = $has_spec == 1 ? Product_logic::get_product_spec( $id ) : "" ; 

        $assign_page = "product/product_input";

        $data = compact('assign_page', 'product', 'product_spec', 'product_extra_column', 'product_spec_column', 'has_spec');

        return view('webbase/content', $data);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {

        $option = array( array("product_name", "like", "%".$_GET['term']."%") );

        $product_data = Product_logic::get_product_data( $option );

        $product_list = Product_logic::product_autocomplete( $product_data );

        echo json_encode($product_list);

        exit();

    }


    public function get_product_spec()
    {

        $product_name = trim($_POST['product_name']);

        $option = array( array("product_name", "=", $product_name) );

        $product_data = Product_logic::get_product_data( $option );

        $product_id = Product_logic::get_product_id_from_collection( $product_data );

        $product_spec = Product_logic::get_product_spec( $product_id );
        
        $product_spec = Product_logic::trans_product_spec_name( $product_spec );

        echo json_encode($product_spec);

        exit();

    }

}
