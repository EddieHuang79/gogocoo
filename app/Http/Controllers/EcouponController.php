<?php

namespace App\Http\Controllers;

use App\logic\Basetool;
use App\logic\Ecoupon_logic;
use Illuminate\Http\Request;
use URL;
use App\logic\Option_logic;
use App\logic\Web_cht;
use Illuminate\Support\Facades\Session;
use App\logic\Store_logic;

class EcouponController extends Basetool
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $txt = Web_cht::get_txt();

        $JsonTxt = json_encode($txt);

        $htmlData = Ecoupon_logic::get_ecoupon_list_template_array();

        $ecoupon = Ecoupon_logic::get_ecoupon_list( $_GET );

        $htmlData = Ecoupon_logic::ecoupon_list_data_bind( $htmlData, $ecoupon );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "ecoupon/ecoupon_list";

        $data = compact('assign_page', 'ecoupon', 'JsonTxt', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $ecoupon = "";

        $site = URL::to('/');

        $txt = Web_cht::get_txt();

        $JsonTxt = json_encode($txt);

        $ErrorMsg = Session::get('ErrorMsg');

        $OriData = Session::get( 'OriData' );

        Session::forget('ErrorMsg');

        Session::forget('OriData');

        $htmlData = Ecoupon_logic::get_ecoupon_input_template_array();

        $htmlData = Ecoupon_logic::ecoupon_input_data_bind( $htmlData, $OriData );

        $htmlData["action"] = "/ecoupon";

        $htmlData["method"] = "post"; 
  
        $htmlJsonData = json_encode($htmlData);

        $assign_page = "ecoupon/ecoupon_input";

        $data = compact('assign_page', 'ecoupon', 'site', 'ecoupon_type', 'ecoupon_match_type', 'ecoupon_send_type', 'store_type', 'ErrorMsg', 'OriData', 'htmlJsonData', 'JsonTxt');

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

        $_this = new self();

        if ( isset($_POST["name"]) ) 
        {

            try
            {

                $error = Ecoupon_logic::test_data( $_POST );

                if ( $error["result"] === true ) 
                {

                    throw new \Exception( json_encode($error["msg"]) );
                
                }

                $data = Ecoupon_logic::insert_format( $_POST );

                Ecoupon_logic::add_ecoupon( $data );

            }
            catch(\Exception $e)
            {

                $data = json_decode($e->getMessage() ,true);

                Session::put( 'OriData', $_POST );

                Session::put( 'ErrorMsg', $_this->show_error_to_user( $data ) );

                return back();

            }

        }

        return redirect("/ecoupon");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        //
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

        $ecoupon = Ecoupon_logic::get_single_ecoupon( (int)$id );

        $txt = Web_cht::get_txt();

        $JsonTxt = json_encode($txt);

        $htmlData = Ecoupon_logic::get_ecoupon_input_template_array( $ecoupon );
  
        $htmlData = Ecoupon_logic::ecoupon_input_data_bind( $htmlData, $ecoupon );

        $htmlData["action"] = "/ecoupon/" . (int)$id;

        $htmlData["method"] = "patch";

        $htmlJsonData = json_encode($htmlData);

        $ErrorMsg = Session::get('ErrorMsg');

        Session::forget('ErrorMsg');

        $assign_page = "ecoupon/ecoupon_input";

        $data = compact('assign_page', 'ecoupon', 'site', 'htmlJsonData', 'JsonTxt', 'ErrorMsg');

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

        $_this = new self();

        try
        {

            $error = Ecoupon_logic::test_data( $_POST );

            if ( $error["result"] === true ) 
            {

                throw new \Exception( json_encode($error["msg"]) );
            
            }

            $data = Ecoupon_logic::update_format( $_POST );

            $ecoupon_id = intval($id);

            Ecoupon_logic::edit_ecoupon( $data, $ecoupon_id );

        }
        catch(\Exception $e)
        {

            $data = json_decode($e->getMessage() ,true);

            Session::put( 'ErrorMsg', $_this->show_error_to_user( $data ) );

            return back();

        }

        return redirect("/ecoupon");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEcouponDiscount()
    {

        $code = isset($_POST["code"]) ? trim($_POST["code"]) : "";

        $shop_car_total = isset($_POST["sub_total"]) ? intval($_POST["sub_total"]) : "";

        $ecouponData = Ecoupon_logic::test_ecoupon_code( $code );

        if ( $ecouponData["result"] === true ) 
        {

            $data = Ecoupon_logic::get_ecoupon_discount_price( (int)$ecouponData["data"]["type"], $ecouponData["data"]["ecoupon_content"], $shop_car_total );
            
        }
        else
        {

            $data = array(
                        "result"            => false,
                        "discount_price"    => 0,
                        "msg"               => $ecouponData["msg"]
                    );

        }

        echo json_encode($data);

        exit();

    }

}
