<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\logic\Edm_logic;
use URL;
use Illuminate\Support\Facades\Session;

class EDMController extends Controller
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

        $edm = Edm_logic::get_edm_list( $_GET );
 
        $htmlData = Edm_logic::edm_list_data_bind( $edm );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "edm/edm_list";

        $data = compact('assign_page', 'edm', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $edm = "";


        $OriData = Session::get( 'OriData' );

        Session::forget( 'OriData' );

        $htmlData = Edm_logic::get_edm_input_template_array();

        $htmlData = Edm_logic::edm_input_data_bind( $htmlData, $OriData );

        $htmlData["action"] = "/edm";

        $htmlData["method"] = "post"; 
  
        $htmlJsonData = json_encode($htmlData);


        $assign_page = "edm/edm_input";

        $data = compact('assign_page', 'htmlJsonData');

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

        if ( isset($_POST["subject"]) ) 
        {

            $data = Edm_logic::insert_format( $_POST );

            $edm_id = Edm_logic::add_edm( $data );

            if ( isset($_POST["mall_shop_id"]) ) 
            {

                $rel_data = Edm_logic::add_edm_rel_format( $_POST["mall_shop_id"], $edm_id );

                Edm_logic::add_edm_rel( $rel_data );
            
            }

        }

        if ( $request->hasFile('edm_list') ) 
        {

            Storage::makeDirectory("edm_list");

            $request->file('edm_list')->storeAs( 'edm_list', $edm_id . ".txt" );

        }

        return redirect("/edm");

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

        $edm = Edm_logic::get_single_edm( (int)$id );

        $edm_rel = Edm_logic::get_edm_rel( (int)$id );


        $htmlData = Edm_logic::get_edm_input_template_array();

        $edm = get_object_vars($edm);
  
        $htmlData = Edm_logic::edm_input_data_bind( $htmlData, $edm );

        $htmlData["action"] = "/edm/" . (int)$id;

        $htmlData["method"] = "patch";

        $htmlJsonData = json_encode($htmlData);


        $assign_page = "edm/edm_input";

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

        $data = Edm_logic::update_format( $_POST );

        $edm_id = intval($id);

        Edm_logic::edit_edm( $data, $edm_id );

        if ( isset($_POST["mall_shop_id"]) ) 
        {

            Edm_logic::del_edm_rel( $edm_id );

            $rel_data = Edm_logic::add_edm_rel_format( $_POST["mall_shop_id"], $edm_id );

            Edm_logic::add_edm_rel( $rel_data );
        
        }

        if ( $request->hasFile('edm_list') ) 
        {

            Storage::makeDirectory("edm_list");

            $request->file('edm_list')->storeAs( 'edm_list', $edm_id . ".txt" );

        }

        return redirect("/edm");

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

        $edm_id = isset($_GET["edm_id"]) ? intval($_GET["edm_id"]) : 0 ;
    
        $edm = Edm_logic::get_single_edm( $edm_id ); 

        $edm = Edm_logic::clone_edm( $edm ); 

 		Edm_logic::add_edm( $edm );

 		return redirect("/edm");

    }

    public function edm_verify_list()
    {

        // get now page

        // Service_logic::get_service_id_by_url_and_save( $request->path() );

        // search bar setting

        // $search_tool = array(5,6);

        // Redis_tool::set_search_tool( $search_tool );

        $_GET["status"] = array( 1 );

        $edm = Edm_logic::get_edm_list( $_GET );
 
        $htmlData = Edm_logic::edm_verify_list_data_bind( $edm );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "edm/edm_verify_list";

        $data = compact('assign_page', 'edm', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    public function verify()
    {

        if ( !empty($_POST["edm_id"]) ) 
        {

            // filter

            $edm_id_array = array_filter($_POST["edm_id"], "intval");

            // 改狀態為待發送

            Edm_logic::change_status( $edm_id_array, 2 ); 

        }

 		return redirect("/edm_verify_list");

    }

    public function edm_cancel_list()
    {

        // get now page

        // Service_logic::get_service_id_by_url_and_save( $request->path() );

        // search bar setting

        // $search_tool = array(5,6);

        // Redis_tool::set_search_tool( $search_tool );
        
        $_GET["status"] = array( 1, 2 );

        $edm = Edm_logic::get_edm_list( $_GET );
 
        $htmlData = Edm_logic::edm_cancel_list_data_bind( $edm );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "edm/edm_cancel_list";

        $data = compact('assign_page', 'edm', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    public function cancel()
    {

        if ( !empty($_POST["edm_id"]) ) 
        {

            // filter

            $edm_id_array = array_filter($_POST["edm_id"], "intval");

            // 改狀態為取消

            Edm_logic::change_status( $edm_id_array, 4 ); 

        }

 		return redirect("/edm_verify_list");

    }

    public function edm_example()
    {

        Storage::makeDirectory("edm_list");

        $pathToFile = storage_path('app/edm_list/edm_example.txt');

        if ( file_exists($pathToFile) === false )
        {

            $data = array(
                            "aaa@gmail.com,Mr.A",
                            "bbb@gmail.com,Mr.B",
                            "ccc@gmail.com,Mr.C",
                            "ddd@gmail.com,Mr.D",
                            "eee@gmail.com,Mr.E",
                        );

            $new_content = implode("\n", $data);

            file_put_contents($pathToFile, $new_content);

        }

        return response()->download($pathToFile);

    }

}
