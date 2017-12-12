<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\logic\ProductCategory_logic;

class ProductCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $product_category_data = ProductCategory_logic::get_product_category_data();

        $assign_page = "product_category/product_category_list";

        $data = compact('assign_page', 'product_category_data');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $ProductCategory = "";

        $parents_category_list = ProductCategory_logic::get_parents_category_list();
 
        $assign_page = "product_category/product_category_input";

        $data = compact('assign_page', 'ProductCategory', 'parents_category_list');

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

        if ( isset($_POST["name"]) ) 
        {

            $data = ProductCategory_logic::insert_main_format( $_POST );

            ProductCategory_logic::add_product_category( $data );

        }

        return redirect("/product_category");

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {

        $ProductCategory = ProductCategory_logic::get_single_product_category( (int)$id );  

        $parents_category_list = ProductCategory_logic::get_parents_category_list( (int)$id );

        $assign_page = "product_category/product_category_input";

        $data = compact('assign_page', 'ProductCategory', 'parents_category_list');

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

        $product_category_id = intval($id);

        $data = ProductCategory_logic::update_main_format( $_POST );

        ProductCategory_logic::edit_product_category( $data, $product_category_id );

        return redirect("/product_category");

    }

    public function get_child_list()
    {

        $parents_id = intval($_POST["parents_id"]);

        $ProductCategory = ProductCategory_logic::get_child_product_category( $parents_id );  

        echo json_encode($ProductCategory);

        exit();

    }

}
