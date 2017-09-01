<?php

namespace App\logic;

use Illuminate\Database\Eloquent\Model;
use App\model\Service;
use Illuminate\Support\Facades\Session;

class Service_logic extends Basetool
{

   public static function insert_format( $data )
   {
         
      $_this = new self();

      $result = array(
                     "name"       	 => isset($data["name"]) ? $_this->strFilter($data["name"]) : "",
                     "link"          => isset($data["link"]) ? trim($data["link"]) : "",
                     "parents_id"    => isset($data["parents_id"]) ? intval($data["parents_id"]) : 0,
                     "status"        => isset($data["active"]) ? intval($data["active"]) : 0,
                     "sort"          => 0,
                     "created_at"    => date("Y-m-d H:i:s"),
                     "updated_at"    => date("Y-m-d H:i:s")
                  );

      return $result;

   }

   public static function update_format( $data )
   {

      $_this = new self();

      $result = array(
                     "name"          => isset($data["name"]) ? $_this->strFilter($data["name"]) : "",
                     "link"          => isset($data["link"]) ? trim($data["link"]) : "",
                     "parents_id"    => isset($data["parents_id"]) ? intval($data["parents_id"]) : 0,
                     "status"        => isset($data["active"]) ? intval($data["active"]) : 0,
                     "updated_at"    => date("Y-m-d H:i:s")
                  );

      return $result;

   }

   public static function get_service_role_auth( $data, $rel_data )
   {

      $auth = array();

      foreach ($rel_data as $row) 
      {
         $auth[$row->service_id][] = $row->role_name;
      }

      foreach ($data as $key => &$row) 
      {
         $row->auth = isset($auth[$row->id]) ? $auth[$row->id] : array() ;
      }

      return $data;

   }  

   public static function menu_format( $data )
   {

      $result = array();

      foreach ($data as $row) 
      {
         if ($row->parents_id == 0) 
         {
            $result[$row->id] = array(
                              "id"     => $row->id,
                              "name"   => $row->name,
                              "link"   => $row->link,
                              "child"  => array()
                           );
         }

         if ($row->parents_id > 0 && isset($result[$row->parents_id]) ) 
         {
            $result[$row->parents_id]["child"][$row->id] = array(
                                                   "id"     => $row->id,
                                                   "name"   => $row->name,
                                                   "link"   => $row->link,
                                                );             
         }

      }

      return $result;

   }

   public static function get_parents_name( $data, $all_service )
   {

		$result = array();

		foreach ($all_service as $row) 
		{
			$result[$row->id] = $row->name;
		}

		foreach ($data as &$row) 
		{
			$row->parents_service = $row->parents_id > 0 ? $result[$row->parents_id] : "無" ;
		}

		return $data;

   }   	

   public static function role_auth_format( $data )
   {

      $result = array();

      foreach ($data as $key => $value)
      {
         $result[] = $value->service_id;
      }

      return $result;

   }

   public static function auth_check( $service_id, $service_data )
   {

      $auth_id = array();

      foreach ($service_data as $parents_id => $parents_data) 
      {
         
         $auth_id[] = $parents_id;

         foreach ($parents_data["child"] as $child_id => $child_data)
         {
            $auth_id[] = $child_id;
         }

      }

      $result = in_array($service_id, $auth_id) ? true : false ;

      return $result;

   }

   public static function get_service( $id )
   {

      return Service::get_service($id);

   }

   public static function get_active_service()
   {

      return Service::get_active_service();

   }

   public static function get_service_data()
   {

      return Service::get_service_data();

   }

   public static function get_service_list( $param = array() )
   {

      $_this = new self();

      $option = array(
                  "service_id"   => !empty($param["role_id"]) ? $_this->get_service_id_by_role(intval($param["role_id"])) : "",
                  "service_name" => !empty($param["service_name"]) ? $_this->strFilter($param["service_name"]) : "",
                  "status"       => !empty($param["status"]) ? intval($param["status"]) : ""
               );

      return Service::get_service_list( $option );

   }

   public static function get_parents_service()
   {

      return Service::get_parents_service();

   }

   public static function menu_list( $data )
   {

      return Service::menu_list($data);

   }

   public static function add_service( $data )
   {

      return Service::add_service($data);

   }

   public static function edit_service( $data, $service_id )
   {

      Service::edit_service($data, $service_id);

   }

   public static function get_service_id_by_role( $role_id )
   {

      $result = array();

      $data = Service::get_service_id_by_role( $role_id );

      foreach ($data as $row) 
      {
         $result[] = $row->service_id;
      }

      return $result;

   }

   public static function get_service_id_by_url_and_save( $url )
   {

      $url = "/".$url;

      $service_id = Service::get_service_id_by_url_and_save( $url )
                              ->mapWithKeys(function ($item) {
                                  return [$item->id];
                              });

      Session::put("service_id", $service_id[0]);
      
   }

   public static function get_unpublic_service_data()
   {

      return Service::get_unpublic_service_data();

   }

   public static function public_service( $data )
   {

      $service_id = intval($data["service_id"]);

      $data = array(
                  "public" => 1
               );

      Service::edit_service($data, $service_id);

   }

   public static function breadcrumb( $service_id, $service_data )
   {

      $result = array();

      $child_parents = array();

      $targer_parents_id = 0 ;

      foreach ($service_data as $parents_id => $parents_data) 
      {
         
         foreach ($parents_data["child"] as $child_id => $child_data)
         {

            $child_parents[$parents_id][] = $child_id;
         
         }

      }

      foreach ($child_parents as $parents_id => $child) 
      {

         if ( in_array($service_id, $child) ) 
         {

            $targer_parents_id = $parents_id;

         }

      }

      if ( $targer_parents_id > 0 ) 
      {
         $result[] = array(
                        "name" => $service_data[$targer_parents_id]["name"],
                        "link" => $service_data[$targer_parents_id]["link"]
                     );

         $result[] = array(
                        "name" => $service_data[$targer_parents_id]["child"][$service_id]["name"],
                        "link" => $service_data[$targer_parents_id]["child"][$service_id]["link"]
                     );
      }


      return $result;

   }

}
