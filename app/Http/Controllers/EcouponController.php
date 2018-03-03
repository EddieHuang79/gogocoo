<?php

namespace App\Http\Controllers;

use App\logic\Basetool;
use App\logic\Ecoupon_logic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EcouponController extends Basetool
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $ecoupon = Ecoupon_logic::get_ecoupon_list( $_GET );

        $htmlData = Ecoupon_logic::ecoupon_list_data_bind( $ecoupon );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "ecoupon/ecoupon_list";

        $data = compact('assign_page', 'ecoupon', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // Ecoupon_logic::send_ecoupon( $send_type = 1, $user_id = 1 );


        $ecoupon = "";

        $OriData = Session::get( 'OriData' );

        Session::forget( 'OriData' );

        $htmlData = Ecoupon_logic::get_ecoupon_input_template_array();

        $htmlData = Ecoupon_logic::ecoupon_input_data_bind( $htmlData, $OriData );

        $htmlData["action"] = "/ecoupon";

        $htmlData["method"] = "post"; 
  
        $htmlJsonData = json_encode($htmlData);

        $assign_page = "ecoupon/ecoupon_input";

        $data = compact('assign_page', 'htmlJsonData', 'JsonTxt');

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

        $ecoupon = Ecoupon_logic::get_single_ecoupon( (int)$id );

        $htmlData = Ecoupon_logic::get_ecoupon_input_template_array();
  
        $htmlData = Ecoupon_logic::ecoupon_input_data_bind( $htmlData, $ecoupon );

        $htmlData["action"] = "/ecoupon/" . (int)$id;

        $htmlData["method"] = "patch";

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "ecoupon/ecoupon_input";

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
