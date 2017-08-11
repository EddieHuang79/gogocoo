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

        $data = compact('assign_page', 'mall');

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

        if (!empty($_POST["mall_product_id"])) 
        {
            
            // 存圖

            if ($request->hasFile('product_image')) 
            {
                
                Storage::makeDirectory("product_image");

                $_POST["product_image_path"] = $request->file('product_image')->store('product_image');
            
            }

            $mall_product_id = intval($_POST["mall_product_id"]);

            // 主表資料

            $data = Mall_logic::update_format( $_POST );

            Mall_logic::edit_mall_product( $data, $mall_product_id );


            // 規格資料

            Mall_logic::del_mall_product_spec( $mall_product_id );

            $data = Mall_logic::insert_spec_format( $_POST );

            if (!empty($data)) 
            {
                Mall_logic::add_mall_product_spec( $data );
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

            $_POST["mall_product_id"] = Mall_logic::add_mall_product( $data );


            // 規格資料

            $data = Mall_logic::insert_spec_format( $_POST );

            if (!empty($data)) 
            {
                Mall_logic::add_mall_product_spec( $data );
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

        $mall = Mall_logic::get_single_mall( $id ); 

        $assign_page = "mall/mall_input";

        $data = compact('assign_page', 'mall');

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
