<?php

namespace App\Http\Controllers;

use DummyFullModelClass;
use Illuminate\Http\Request;
use App\logic\Mall_logic;
use Illuminate\Support\Facades\Storage;


class MallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $mall = Mall_logic::get_mall_list( $_GET );

        $assign_page = "mall/mall_list";

        $data = compact('assign_page', 'mall');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $mall = "";
 
        $assign_page = "mall/mall_input";

        $child_product = Mall_logic::get_mall_service_list();

        $data = compact('assign_page', 'mall', 'child_product');

        return view('webbase/content', $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // 存圖

        if ($request->hasFile('product_image')) 
        {
            
            Storage::makeDirectory("product_image");

            $_POST["product_image_path"] = $request->file('product_image')->store('product_image');
        
        }

        if ( isset($_POST["product_name"]) ) 
        {

            // 主表資料

            $data = Mall_logic::insert_format( $_POST );

            $_POST["mall_shop_id"] = Mall_logic::add_mall_shop( $data );

            // 子商品資料

            $data = Mall_logic::insert_child_product_format( $_POST );

            if (!empty($data)) 
            {

                Mall_logic::add_child_product( $data );

            }

        }

        return redirect("/mall");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {


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

        $child_product = Mall_logic::get_mall_service_list();

        $id = intval($id);

        $mall_product_rel = Mall_logic::get_mall_service_rel( array($id) ); 

        $mall_product_rel = isset($mall_product_rel[$id]) ? $mall_product_rel[$id] : array() ; 

        $mall = Mall_logic::get_single_mall( $id ); 

        $assign_page = "mall/mall_input";

        $data = compact('assign_page', 'mall', 'child_product', 'mall_product_rel');

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
    public function update(Request $request, $id)
    {

        $_POST["mall_shop_id"] = intval($id);

        // 存圖

        if ($request->hasFile('product_image')) 
        {
            
            Storage::makeDirectory("product_image");

            $_POST["product_image_path"] = $request->file('product_image')->store('product_image');

            $ori_image = Mall_logic::get_mall_image( $_POST["mall_shop_id"] );

            if ( !empty($ori_image->pic) && file_exists( $ori_image->pic ) ) 
            {
             
                unlink( $ori_image->pic );

            }
        
        }

        $mall_shop_id = $_POST["mall_shop_id"];

        // 主表資料

        $data = Mall_logic::update_format( $_POST );

        Mall_logic::edit_mall_shop( $data, $mall_shop_id );

        // 子商品資料

        Mall_logic::del_child_product( $mall_shop_id );

        $data = Mall_logic::insert_child_product_format( $_POST );

        if (!empty($data)) 
        {

            Mall_logic::add_child_product( $data );
            
        }

        return redirect("/mall");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\lain  $lain
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

}
