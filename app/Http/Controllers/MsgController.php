<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Msg_logic;
use App\logic\Service_logic;


class MsgController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // get now page

        // Service_logic::get_service_id_by_url_and_save( $request->path() );

        // search bar setting

        // $search_tool = array(5,6);

        // Redis_tool::set_search_tool( $search_tool );

        $msg = Msg_logic::get_msg_list( $_GET );
 
        $assign_page = "msg/msg_list";

        $data = compact('assign_page', 'msg');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $msg = "";
 
        $assign_page = "msg/msg_input";

        $msg_option = Msg_logic::get_msg_option(); 

        $data = compact('assign_page', 'msg_option', 'msg');

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

        if (!empty($_POST["msg_id"])) 
        {
            
            $data = Msg_logic::update_format( $_POST );

            $msg_id = intval($_POST["msg_id"]);

            Msg_logic::edit_msg( $data, $msg_id );

        }
        else
        {

            $data = Msg_logic::insert_format( $_POST );

            Msg_logic::add_msg( $data );

        }

        return redirect("/msg");

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

        $msg = Msg_logic::get_single_msg( $id ); 

        $assign_page = "msg/msg_input";

        $msg_option = Msg_logic::get_msg_option(); 

        $data = compact('assign_page', 'msg_option', 'msg');

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
        //
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


    public function clone()
    {

        $msg_id = intval($_GET["msg_id"]);
    
        $msg = Msg_logic::get_single_msg( $msg_id ); 

        $msg = Msg_logic::clone_msg( $msg ); 

 		Msg_logic::add_msg( $msg );

 		return redirect("/msg");

    }

}
