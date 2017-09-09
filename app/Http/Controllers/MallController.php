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


        if (!empty($_POST["mall_shop_id"])) 
        {
            
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

            $mall_shop_id = intval($_POST["mall_shop_id"]);

            // 主表資料

            $data = Mall_logic::update_format( $_POST );

            Mall_logic::edit_mall_shop( $data, $mall_shop_id );


            // 規格資料

            // Mall_logic::del_mall_product_spec( $mall_shop_id );

            // $data = Mall_logic::insert_spec_format( $_POST );

            // if (!empty($data)) 
            // {

            //     Mall_logic::add_mall_product_spec( $data );

            // }


            // 子商品資料

            Mall_logic::del_child_product( $mall_shop_id );

            $data = Mall_logic::insert_child_product_format( $_POST );

            if (!empty($data)) 
            {

                Mall_logic::add_child_product( $data );
                
            }

        }
        else
        {

            // 存圖

            if ($request->hasFile('product_image')) 
            {
                
                Storage::makeDirectory("product_image");

                $_POST["product_image_path"] = $request->file('product_image')->store('product_image');
            
            }

            // 主表資料

            $data = Mall_logic::insert_format( $_POST );

            $_POST["mall_shop_id"] = Mall_logic::add_mall_shop( $data );


            // 規格資料

            // $data = Mall_logic::insert_spec_format( $_POST );

            // if (!empty($data)) 
            // {

            //     Mall_logic::add_mall_product_spec( $data );

            // }

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
    public function show()
    {
        //
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

        $mall_product_rel = Mall_logic::get_mall_service_rel( array($id) ); 

        $mall_product_rel = $mall_product_rel[$id]; 

        $mall = Mall_logic::get_single_mall( $id ); 

        // dd($mall_product_rel);

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
    public function update(Request $request)
    {
        //
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
