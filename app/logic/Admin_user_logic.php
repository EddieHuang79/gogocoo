<?php

namespace App\logic;

use App\model\Admin_user;
use Illuminate\Support\Facades\Session;
use App\logic\Shop_logic;

class Admin_user_logic extends Basetool
{

   protected $user_id;

   protected $free_user_limit = 5;

   public function __construct()
   {

      $Login_user = Session::get('Login_user');

      // user_id

      $this->user_id = $Login_user["user_id"];

   }

   public static function insert_format( $data )
   {
         
         $_this = new self();

         $result = array(
                        "account"       => isset($data["account"]) ? trim($data["account"]) : "",
                        "password"      => isset($data["password"]) ? bcrypt($_this->strFilter($data["password"])) : "",
                        "real_name"     => isset($data["real_name"]) ? $_this->strFilter($data["real_name"]) : "",
                        "mobile"        => isset($data["mobile"]) ? $_this->strFilter($data["mobile"]) : "",
                        "parents_id"    => isset($data["parents_id"]) ? intval($data["parents_id"]) : 0,
                        "status"        => isset($data["active"]) ? intval($data["active"]) : 0,
                        "created_at"    => date("Y-m-d H:i:s"),
                        "updated_at"    => date("Y-m-d H:i:s")
                     );

         return $result;

   }

   public static function update_format( $data )
   {

         $_this = new self();

         $result = array(
                        "real_name"     => isset($data["real_name"]) ? $_this->strFilter($data["real_name"]) : "",
                        "mobile"        => isset($data["mobile"]) ? $_this->strFilter($data["mobile"]) : "",
                        "status"        => isset($data["active"]) ? intval($data["active"]) : 0,
                        "updated_at"    => date("Y-m-d H:i:s")
                     );

         if (!empty($data["password"])) 
         {
            $result["password"] = bcrypt($_this->strFilter($data["password"]));
         }


         return $result;

   }

   public static function get_user_role_auth( $data, $rel_data )
   {

         $auth = array();

         foreach ($rel_data as $row)
         {
            $auth[$row->user_id][] = $row->name;
         }

         foreach ($data as &$row) 
         {
            $row->auth = isset($auth[$row->id]) ? $auth[$row->id] : array() ;
         }

         return $data;

   }  

   public static function add_user_role_format( $user_id, $data )
   {

         $result = array();

         foreach ($data as $key => $value)
         {
            $result[] = array(
                              "user_id"   => intval($user_id),
                              "role_id"   => intval($value)
                        );
         }

         return $result;

   }

   public static function get_user( $id = 0 )
   {

         return Admin_user::get_user( $id );

   }

   public static function get_user_list( $param = array() )
   {

         $_this = new self();

         $option = array(
                     "account"   => !empty($param["account"]) ? $_this->strFilter($param["account"]) : "",
                     "real_name" => !empty($param["real_name"]) ? $_this->strFilter($param["real_name"]) : "",
                     "user_id"   => !empty($param["role_id"]) ?  $_this->get_user_id_by_role(intval($param["role_id"])) : "",
                     "parents_id"=> !empty($param["parents_id"]) ?  intval($param["parents_id"]) : "",
                     "status"    => !empty($param["status"]) ? intval($param["status"]) : ""
                  );

         return Admin_user::get_user_list( $option );

   }

   public static function get_user_role()
   {

         return Admin_user::get_user_role();
         
   }

   public static function get_user_role_by_id( $id )
   {

         return Admin_user::get_user_role_by_id( $id );
         
   }

   public static function add_user( $data )
   {

         return Admin_user::add_user( $data );
         
   }

   public static function edit_user( $data, $user_id )
   {

         Admin_user::edit_user( $data, $user_id );
         
   }

   public static function add_user_role( $data )
   {

         Admin_user::add_user_role( $data );
         
   }

   public static function delete_user_role( $user_id )
   {

         Admin_user::delete_user_role( $user_id );
         
   }

   public static function get_user_id( $data )
   {

         return Admin_user::get_user_id( $data );
         
   }

   public static function get_user_id_by_role( $role_id )
   {

         $result = array();

         $data = Admin_user::get_user_id_by_role( $role_id );

         foreach ($data as $row) 
         {
            $result[] = $row->user_id;
         }

         return $result;
         
   }

   public static function get_rand_string( $len = 3 )
   {

         $_this = new self();

         $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

         $repeat = 1;

         while ($repeat > 0) 
         {

            $result = "";

            for ($i=0; $i < $len; $i++) 
            { 
                $result .= $string[rand(0, strlen($string)-1)];
            }

            $repeat = $_this->check_store_code_repeat( $result );

         }


        return $result;

   }

   public static function check_store_code_repeat( $store_code )
   {

         return Admin_user::check_store_code_repeat( $store_code );

   }

   public static function cnt_child_account()
   {

         $_this = new self();

         $user_id = $_this->user_id;

         // 免費額度

         $free_user_limit = $_this->free_user_limit;

         // 已創造帳號數

         $total = Admin_user::cnt_child_account( $user_id );

         // 剩餘免費帳號數

         $left_free_account = $free_user_limit - $total;

         // 已購買的子帳數

         $buy_account_cnt = Shop_logic::get_shop_record_by_id( "child_account" );

         // 剩餘額度

         $left = $left_free_account + $buy_account_cnt;


         $result = array(
                     "free"   => $left_free_account > 0 ? $left_free_account : 0,
                     "buy"    => $buy_account_cnt,
                     "left"   => $left
                  );

         return $result;     
   }

   public static function is_admin( $data )
   {

         $result = $data["user_id"] == 1 ? true :false;

         return $result;

   }

   public static function is_sub_admin( $data )
   {

         $data = Admin_user::is_sub_admin( $data["user_id"] );

         $result = !empty($data) && $data->parents_id > 0 ? false : true; 

         return $result;   

   }

   public static function get_parents_id( $data )
   {

         $data = Admin_user::is_sub_admin( $data["user_id"] );

         return $data->parents_id;   

   }

   public static function insert_store( $data )
   {

         return Admin_user::insert_store( $data );
         
   }

   public static function get_user_photo()
   {

         $result = new \stdClass();

         $Login_user = Session::get( 'Login_user' );

         if ( !empty($Login_user) ) 
         {

            $result = Admin_user::get_user_photo( $Login_user["user_id"] );

         }

         $result->photo = !empty($result->photo) ? $result->photo : "_images/user_default.png" ;

         return $result;
         
   }

   public static function edit_user_photo( $photo_upload_files )
   {

         $Login_user = Session::get( 'Login_user' );

         $result = Admin_user::edit_user_photo( $photo_upload_files, $Login_user["user_id"] );

         return $result;
         
   }

}