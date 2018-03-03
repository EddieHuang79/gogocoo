<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Promo_logic;
use Illuminate\Support\Facades\Session;

class PromoPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $id = isset($_GET['mall_shop_id']) ? intval($_GET['mall_shop_id']) : 0 ;

        if ( empty($id) ) 
        {
        
            return redirect("/mall");

        }

        $promo = Promo_logic::get_promo_price( $id ); 

        $htmlData = Promo_logic::promo_list_data_bind( $promo );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "promo/promo_list";

        $data = compact('assign_page', 'promo', 'id', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $ErrorMsg = Session::get('ErrorMsg');

        $OriData = Session::get( 'OriData' );

        Session::forget('ErrorMsg');

        Session::forget('OriData');

        $mall_shop_id = isset($_GET["mall_shop_id"]) ? intval($_GET["mall_shop_id"]) : 0 ;

        if ( empty($mall_shop_id) ) 
        {
        
            return redirect("/mall");

        }


        $htmlData = Promo_logic::get_promo_input_template_array();

        $htmlData = Promo_logic::promo_input_data_bind( $htmlData, $OriData );

        $htmlData["action"] = "/promo";

        $htmlData["method"] = "post"; 
  
        $htmlJsonData = json_encode($htmlData);


        $assign_page = "promo/promo_input";

        $data = compact('assign_page', 'mall_shop_id', 'ErrorMsg', 'OriData', 'htmlJsonData');

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
    
        try
        {

            // 主表資料

            $data = Promo_logic::insert_format( $_POST );

            // 判斷日期重複

            $is_repeat = Promo_logic::is_promo_date_repeat( $data );

            if ( $is_repeat === true ) 
            {

                throw new \Exception( json_encode($data) );
            
            }

            // 寫入資料

            Promo_logic::add_promo_data( $data );

        }
        catch(\Exception $e)
        {

            $data = json_decode($e->getMessage() ,true);

            if ( isset($data["start_date"]) && isset($data["end_date"]) ) 
            {

                $data["start_date"] = date("Y-m-d", strtotime($data["start_date"]));

                $data["end_date"] = date("Y-m-d", strtotime($data["end_date"]));

                Session::put( 'OriData', $data );

                Session::put( 'ErrorMsg', 1 );
                    
            }

            return back();

        }

        $mall_shop_id = isset( $data["mall_shop_id"] ) ? $data["mall_shop_id"] : 0 ;

        return redirect("/promo?mall_shop_id=".$mall_shop_id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $promo = Promo_logic::get_single_promo_data( (int)$id );  

        $promo = get_object_vars($promo); 

        $htmlData = Promo_logic::get_promo_input_template_array();
  
        $htmlData = Promo_logic::promo_input_data_bind( $htmlData, $promo );

        $htmlData["action"] = "/promo/" . (int)$id;

        $htmlData["method"] = "patch";

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "promo/promo_input";

        $data = compact('assign_page', 'htmlJsonData');

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

        try
        {

            // 主表資料

            $data = Promo_logic::update_format( $_POST );

            // 判斷日期重複

            $is_repeat = Promo_logic::is_promo_date_repeat( $data, (int)$id );

            if ( $is_repeat === true ) 
            {

                throw new \Exception( json_encode($data) );
            
            }

            // 寫入資料

            Promo_logic::edit_promo_data( $data, (int)$id );

        }
        catch(\Exception $e)
        {

            Session::put( 'ErrorMsg', 1 );

            return back();

        }

        return redirect("/promo?mall_shop_id=".$data['mall_shop_id']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
