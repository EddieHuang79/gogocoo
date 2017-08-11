<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Role_logic;
use App\logic\Service_logic;
use App\logic\Redis_tool;

class RoleController extends Controller
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

        $search_tool = array(5,6);

        Redis_tool::set_search_tool( $search_tool );

        $role = Role_logic::get_role_list( $_GET );
 
        $assign_page = "role/role_list";

        $data = compact('role', 'assign_page');

        return view('webbase/content', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $role = "";
 
        $assign_page = "role/role_input";

        $menu_data = Service_logic::get_active_service();

        $menu_list = Service_logic::menu_format( $menu_data );

        $data = compact('role', 'assign_page', 'menu_list');

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

        if (!empty($_POST["role_id"])) 
        {
            
            // role

            $data = Role_logic::update_format( $_POST );

            $role_id = intval($_POST["role_id"]);

            Role_logic::edit_role( $data, $role_id );

            // role service delete add

            Role_logic::delete_role_service( $role_id ) ;

            if (!empty($_POST["auth"])) 
            {
                $data = Role_logic::add_role_service_format( $role_id, 0, $_POST["auth"] );

                Role_logic::add_role_service( $data );
            }

        }
        else
        {
            // role

            $data = Role_logic::insert_format( $_POST );
         
            $role_id = Role_logic::add_role( $data );

            // role service add
            
            if (!empty($_POST["auth"])) 
            {
                $data = Role_logic::add_role_service_format( $role_id, 0, $_POST["auth"] );

                $data = Role_logic::add_role_service( $data );
            }

        }

        return redirect("/role");

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

        $role = Role_logic::get_role( $id );
 
        $assign_page = "role/role_input";

        $menu_data = Service_logic::get_active_service();

        $menu_list = Service_logic::menu_format( $menu_data );

        $role_service_data = Role_logic::get_role_service( $id );

        $role_service = Service_logic::role_auth_format( $role_service_data );

        $data = compact('role', 'assign_page', 'menu_list', 'role_service');

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
}
