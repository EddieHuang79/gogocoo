<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Role_logic;
use App\logic\Service_logic;
use App\logic\Redis_tool;
use App\logic\Web_cht;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // get now page

        Service_logic::get_service_id_by_url_and_save( $request->path() );

        // search bar setting

        $search_tool = array(4,5,7);

        Redis_tool::set_search_tool( $search_tool );


        $service_data = Service_logic::get_service_data();

        $service_list = Service_logic::get_service_list( $_GET );

        $role_service = Role_logic::get_role_service();

        $service = Service_logic::get_parents_name( $service_list, $service_data );

        $service = Service_logic::get_service_role_auth( $service, $role_service );

        $assign_page = "service/service_list";

        $data = compact('service', 'assign_page');

        return view('webbase/content', $data);        
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $service = "";
 
        $assign_page = "service/service_input";

        $role_list = Role_logic::get_active_role();

        $parents_service = Service_logic::get_parents_service();

        $data = compact('service', 'assign_page', 'role_list', 'parents_service');

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

        if (!empty($_POST["service_id"])) 
        {
            
            // service

            $data = Service_logic::update_format( $_POST );

            $service_id = intval($_POST["service_id"]);

            Service_logic::edit_service( $data, $service_id );

            // service role delete add

            Role_logic::delete_role_service( 0, $service_id );
            
            if (!empty($_POST["auth"])) 
            {
                $data = Role_logic::add_role_service_format( 0, $service_id, $_POST["auth"] );

                Role_logic::add_role_service( $data );
            }

        }
        else
        {
            // service

            $data = Service_logic::insert_format( $_POST );
         
            $service_id = Service_logic::add_service( $data );

            // user role add

            if (!empty($_POST["auth"])) 
            {
                $data = Role_logic::add_role_service_format( 0, $service_id, $_POST["auth"] );

                Role_logic::add_role_service( $data );
            }

        }

        Redis_tool::del_menu_data_all();

        return redirect("/service");

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

        $service = Service_logic::get_service( $id );
 
        $assign_page = "service/service_input";

        $role_list = Role_logic::get_active_role();

        $parents_service = Service_logic::get_parents_service();

        $role_service = Role_logic::get_role_service( 0, $id );

        $data = compact('service', 'assign_page', 'role_list', 'parents_service', 'role_service');

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
    public function destroy($id)
    {
        //
    }

    /**
     * Service Public
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function service_public()
    {

        $txt = Web_cht::get_txt();

        $service = Service_logic::get_unpublic_service_data();

        $assign_page = "service/service_public";

        $public_txt = array(
                            1 => $txt["already_public"],
                            2 => $txt["not_public"],
                            3 => $txt["forbid_public"],
                            4 => $txt["free_public"]
                        );

        $data = compact('assign_page', 'service', 'public_txt');

        return view('webbase/content', $data);

    }

    /**
     * Service Public Process
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function service_public_process()
    {

        $service = Service_logic::public_service( $_GET );

        return redirect("/service_public");

    }


}
