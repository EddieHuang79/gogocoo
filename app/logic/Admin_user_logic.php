<?php

namespace App\logic;

use App\model\Admin_user;
use Illuminate\Support\Facades\Session;
use App\logic\Shop_logic;
use App\logic\Web_cht;
use App\logic\Msg_logic;
use App\logic\Role_logic;

class Admin_user_logic extends Basetool
{

   protected $user_id;

   protected $free_user_limit = 4;

   protected $status_txt = array();

   public function __construct()
   {

      $Login_user = Session::get('Login_user');

      // user_id

      $this->user_id = $Login_user["user_id"];

      $txt = Web_cht::get_txt();

      $this->status_txt = array(
                                 1  => $txt["enable"],
                                 2  => $txt["disable"]
                              );

   }

   public static function insert_format( $data )
   {
         
         $_this = new self();

         $result = array();

         if ( !empty($data) && is_array($data) ) 
         {

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

         }

         return $result;

   }

   public static function update_format( $data )
   {

         $_this = new self();

         $result = array();

         if ( !empty($data) && is_array($data) ) 
         {

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

         }

         return $result;

   }

   public static function get_user_role_auth( $data, $rel_data )
   {

         $auth = array();

         $result = array();

         if ( !empty($data) && !empty($rel_data) ) 
         {

            foreach ($rel_data as $row)
            {

               $auth[$row->user_id][] = $row->name;
            
            }

            foreach ($data as &$row) 
            {
            
               $row->auth = isset($auth[$row->id]) ? $auth[$row->id] : array() ;
            
            }

            $result = $data;

         }

         return $result;

   }  

   public static function add_user_role_format( $user_id, $data )
   {

         $result = array();

         if ( !empty($data) && is_array($data) ) 
         {

            foreach ($data as $key => $value)
            {
               $result[] = array(
                                 "user_id"   => intval($user_id),
                                 "role_id"   => intval($value)
                           );
            }

         }

         return $result;

   }

   public static function get_user( $id = 0 )
   {

         $result = false;

         if ( !empty($id) && is_int($id) ) 
         {

            $result = Admin_user::get_user( $id );

         }

         return $result;

   }

   public static function get_user_list( $param = array() )
   {

         $result = array();

         $_this = new self();

         $status_txt = $_this->status_txt;

         $Login_user = Session::get('Login_user');

         $option = array(
                     "account"   => !empty($param["account"]) ? $_this->strFilter($param["account"]) : "",
                     "real_name" => !empty($param["real_name"]) ? $_this->strFilter($param["real_name"]) : "",
                     "user_id"   => !empty($param["role_id"]) ?  $_this->get_user_id_by_role(intval($param["role_id"])) : "",
                     "parents_id"=> !empty($Login_user["user_id"]) ? intval($Login_user["user_id"]) : "",
                     "status"    => !empty($param["status"]) ? intval($param["status"]) : ""
                  );

         $result = Admin_user::get_user_list( $option );

         if ( $result->isNotEmpty() ) 
         {

            $user_id = array();

            foreach ($result as $row) 
            {
            
               $user_id[] = $row->id;

            }

            // 計算帳號截止日

            $cnt_deadline = Shop_logic::count_deadline( $user_id, "child_account" );

            foreach ($result as &$row) 
            {

               $row->deadline = Shop_logic::get_deadline( $row, $cnt_deadline );

               $row->status_txt = isset($status_txt[$row->status]) ? $status_txt[$row->status] : "" ;

            }

            // 帶出使用者角色陣列

            $user_role = $_this->get_user_role();

            $result = $_this->get_user_role_auth( $result, $user_role );

         }

         return $result;

   }

   public static function get_user_role()
   {

         return Admin_user::get_user_role();
         
   }

   public static function get_user_role_by_id( $id )
   {

         $result = array();

         if ( !empty($id) && is_int($id) ) 
         {

            $result = Admin_user::get_user_role_by_id( $id );

         }

         return $result;

   }

   public static function add_user( $data )
   {

         $result = false;

         if ( !empty($data) && is_array($data) ) 
         {

            $result = Admin_user::add_user( $data );

         }

         return $result ;
         
   }

   public static function edit_user( $data, $user_id )
   {

         $result = false;

         if ( !empty($data) && is_array($data) && !empty($user_id) && is_int($user_id) ) 
         {

            Admin_user::edit_user( $data, $user_id );

            $result = true;

         }

         return $result;
         
   }

   public static function add_user_role( $data )
   {

         $result = false;

         if ( !empty($data) && is_array($data) ) 
         {

             Admin_user::add_user_role( $data );

            $result = true;

         }

         return $result;

   }

   public static function delete_user_role( $user_id )
   {

         $result = false;

         if ( !empty($user_id) && is_int($user_id) ) 
         {

            Admin_user::delete_user_role( $user_id );

            $result = true;

         }

         return $result;
         
   }

   public static function get_user_id( $data )
   {

         $result = array();

         if ( !empty($data) && is_array($data) ) 
         {

            $result = Admin_user::get_user_id( $data );

         }

         return $result;
         
   }

   public static function get_user_id_by_role( $role_id )
   {

         $result = array();

         if ( !empty($role_id) && is_int($role_id) ) 
         {

            $data = Admin_user::get_user_id_by_role( $role_id );

            if ( !empty($data) ) 
            {

               foreach ($data as $row) 
               {

                  if ( is_object($row) ) 
                  {

                     $result[] = $row->user_id;

                  }    
               
               }

            }

         }

         return $result;
         
   }

   public static function get_rand_string( $len = 3 )
   {

         $_this = new self();

         $len = is_int($len) ? $len : 3 ;

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

         $result = 0;

         if ( !empty($store_code) && is_string($store_code) ) 
         {

            $result = Admin_user::check_store_code_repeat( $store_code );

         }

         return $result;

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

         $buy_account_cnt = Shop_logic::get_count_by_action_key( "child_account" );

         $buy_account_cnt = array_shift($buy_account_cnt);

         // 剩餘額度

         $left = $left_free_account + $buy_account_cnt["count"];


         $result = array(
                     "free"               => $left_free_account > 0 ? $left_free_account : 0,
                     "buy"                => $buy_account_cnt["count"],
                     "buy_spec_data"      => $buy_account_cnt["data"],
                     "left"               => $left
                  );

         return $result;     

   }

   public static function is_admin( $data )
   {

         $result = !empty($data) && is_array($data) && $data["user_id"] == 1 ? true : false;

         return $result;

   }

   public static function is_sub_admin( $data )
   {

         $result = false;

         if ( !empty($data) && is_array($data) )
         {

            $data = Admin_user::is_sub_admin( $data["user_id"] );

            $result = !empty($data) && $data->parents_id > 0 ? false : true; 

         } 

         return $result;   

   }

   public static function get_parents_id( $data )
   {

         $result = 0 ;

         if ( !empty($data) && is_array($data) )
         {

            $data = Admin_user::is_sub_admin( $data["user_id"] );

            $result = is_object($data) ? (int)$data->parents_id : 0 ; 

         } 

         return $result;   

   }

   public static function insert_store( $data )
   {

         $result = false;

         if ( !empty($data) && is_array($data) ) 
         {

            $result = Admin_user::insert_store( $data );

         }

         return $result;
         
   }

   public static function get_user_photo()
   {

         $result = new \stdClass();

         $Login_user = Session::get( 'Login_user' );

         if ( !empty($Login_user) ) 
         {

            $result = Admin_user::get_user_photo( $Login_user["user_id"] );

            $result->photo = !empty($result->photo) ? $result->photo : "_images/user_default.png" ;

         }

         return $result;
         
   }

   public static function edit_user_photo( $photo_upload_files )
   {

         $_this = new self();

         $result = false;

         if ( !empty($photo_upload_files) ) 
         {

            $Login_user = Session::get( 'Login_user' );

            $ori_image = $_this->get_user_image( (int)$Login_user["user_id"] );

            if ( !empty($ori_image->photo) && file_exists( $ori_image->photo ) ) 
            {

               unlink( $ori_image->photo );

            }

            $result = Admin_user::edit_user_photo( $photo_upload_files, $Login_user["user_id"] );

         }

         return $result;
         
   }


   // 擴展帳號期限

   public static function extend_user_deadline( $user_id, $data )
   {

      $result = false;

      if ( !empty($user_id) && is_int($user_id) && !empty($data) && is_array($data) ) 
      {

         Shop_logic::add_use_record( (int)$user_id, $data, $type = 1 );

         $result = true;

      }

      return $result;
         
   }


   // 回傳帳號原始圖片

   public static function get_user_image( $user_id )
   {

      $result = false;

      if ( !empty($user_id) && is_int($user_id) ) 
      {

         $result = Admin_user::get_user_image( $user_id );

      }

      return $result;
         
   }


   // 帳號驗證

   public static function account_verify( $data )
   {

      $_this = new self();

      $txt = Web_cht::get_txt();

      $result = array();

      $ErrorMsg = array();

      if ( !empty($data) && is_array($data) ) 
      {

         try 
         {

            // 密碼長度

            if ( !empty($data["password"]) && !$_this->string_length( $data["password"] ) ) 
            {
                          
               $ErrorMsg[] = $txt["pwd_length_fail"];

            }


            // 密碼複雜度

            if ( !empty($data["password"]) && !$_this->pwd_complex( $data["password"] ) ) 
            {

               $ErrorMsg[] = $txt["pwd_format_fail"];

            }


            // 手機

            if ( !$_this->is_phone( $data["mobile"] ) ) 
            {

               $ErrorMsg[] = $txt["phone_format_fail"];

            }


            // 姓名

            if ( !$_this->strFilter( $data["real_name"] ) ) 
            {

               $ErrorMsg[] = $txt["real_name_fail"];

            }


            // 角色

            if ( !isset($data["auth"]) ) 
            {

               $ErrorMsg[] = "未選擇角色！";

            }


            if ( !empty($ErrorMsg) ) 
            {

               throw new \Exception(json_encode($ErrorMsg));

            }

         } 
         catch (\Exception $e) 
         {

            $result = json_decode($e->getMessage() ,true);
         
         }

      }


      return $result;

   }


   // 取得所有關連id

   public static function get_rel_user_id( $user_id )
   {

         $result = array() ;

         if ( !empty($user_id) && is_int($user_id) )
         {

            $data = Admin_user::get_rel_user_id( $user_id );

            foreach ($data as $row) 
            {

               $result[] = $row->id;

            }

         } 

         return $result;   

   }


   // 找出距離免費試用到期日倒數三天的帳號

   public static function get_expiring_user( $day = 27 )
   {

      $result = array();

      if ( !empty($day) && is_int($day) ) 
      {
   
         $result = Admin_user::get_expiring_user( $day );
   
      }

      return $result;

   }


   // 用店鋪編號找到使用者

   public static function get_user_by_store_id( $store_id )
   {

      $result = array();

      if ( !empty($store_id) && is_int($store_id) ) 
      {
   
         $result = Admin_user::get_user_by_store_id( $store_id );
   
      }

      return $result;

   }


   // 邀請碼編碼

   public static function invite_code_encode( $store_id )
   {

      $_this = new self();

      $result = "";

      if ( !empty($store_id) && is_int($store_id) ) 
      {

         $prefix = "";

         $suffix = "";

         for ($i=0; $i < 3; $i++) 
         { 

            $prefix_rand = mt_rand( 1 , 25 );
         
            $prefix .= $_this->ASC_Decimal_value( $prefix_rand );

            $suffix_rand = mt_rand( 1 , 25 );

            $suffix .= $_this->ASC_Decimal_value( $suffix_rand );

         }

         $result = "InviteCode_" . $store_id ;

         $result = $prefix . base64_encode( $result ) . $suffix ;
         
      }

      return $result;

   }


   // 邀請碼解碼 回傳store_id

   public static function invite_code_decode( $code )
   {

      $result = "";

      if ( !empty($code) && is_string($code) ) 
      {

         $code = substr($code, 3, strlen($code) - 6 ) ;

         $decode = base64_decode( $code ) ;

         $tmp = explode("_", $decode);

         $result = isset($tmp[1]) ? intval($tmp[1]) : 0;

      }      

      return $result;

   }


   // 寫入邀請記錄 邀請store id, 被邀請store id

   public static function invite_record( $invite_store_id, $invited_store_id )
   {

      $result = false;

      if ( !empty($invite_store_id) && is_int($invite_store_id) && !empty($invited_store_id) && is_int($invited_store_id) ) 
      {

         $data = array(
                     "invite_store_id"    => $invite_store_id,
                     "invited_store_id"   => $invited_store_id,
                     "created_at"         => date("Y-m-d H:i:s"),
                     "updated_at"         => date("Y-m-d H:i:s")
                  );

         Admin_user::add_invite_record( $data );

         $result = true;
         
      }

      return $result;

   }


   // 寫入邀請記錄 邀請store id, 被邀請store id

   public static function send_invite_code_process( $invite_code, $user_id, $store_id )
   {

      $_this = new self();

      $result = false;

      if ( !empty($invite_code) && is_string($invite_code) && !empty($user_id) && is_int($user_id) && !empty($store_id) && is_int($store_id) ) 
      {

         try 
         {
            
               // 解碼，尋找發送來源

               $invite_store_id = $_this->invite_code_decode( $invite_code );

               if ( empty($invite_store_id) || !is_int($invite_store_id) ) 
               {

                     $ErrorMsg = array(
                                    "subject" => "邀請碼無法識別！",
                                    "content" => "邀請碼無法識別，請確認是否有誤！",
                                 );

                     throw new \Exception( json_encode($ErrorMsg) );
                  
               }

               // 檢查被邀請碼是否被使用過

               $invite_code_not_effective = $_this->check_invite_qualifications( $type = 1, $invite_store_id );

               if ( $invite_code_not_effective === true ) 
               {

                     $ErrorMsg = array(
                                    "subject" => "邀請碼已被使用過！",
                                    "content" => "邀請碼已失效！",
                                 );

                     throw new \Exception( json_encode($ErrorMsg) );
                  
               }

               // 檢查註冊人是否已領過

               $already_get_gift = $_this->check_invite_qualifications( $type = 2, $store_id );

               if ( $already_get_gift === true ) 
               {

                     $ErrorMsg = array(
                                    "subject" => "您已領取過禮物！",
                                    "content" => "禮物已領取過，無法重複領取！",
                                 );

                     throw new \Exception( json_encode($ErrorMsg) );
                  
               }

               // 邀請禮 id 2

               Shop_logic::add_free_gift( $invite_store_id, 2 );

               // 被邀請禮 id 3

               Shop_logic::add_free_gift( $store_id, 3 );

               // 領取記錄 邀請store id, 被邀請store id

               $_this->invite_record( $invite_store_id, $store_id );

               // 寫入訊息

               $subject = "邀請碼優惠已送出！";

               $content = "邀請碼優惠已發送至您的帳號，請前往GO商城 > 購買紀錄檢視！";

               Msg_logic::add_normal_msg( $subject, $content, $user_id );


         } 
         catch (\Exception $e) 
         {
          
               // 寫入錯誤訊息

               $error_msg = json_decode($e->getMessage() ,true);

               Msg_logic::add_normal_msg( $error_msg["subject"], $error_msg["content"], $user_id );

         }
          
      }      

      return $result;

   }

   public static function check_invite_qualifications( $type, $store_id )
   {

      $result = false;

      if ( !empty($type) && is_int($type) && !empty($store_id) && is_int($store_id) ) 
      {

         $column = $type === 1 ? "invite_store_id" : "invited_store_id" ;

         $data = Admin_user::check_invite_qualifications( $column, $store_id );
         
         $result = $data->isNotEmpty() === true ? true : false ; 

      }

      return $result;

   }


   // 確認邀請按鈕是否顯示

   public static function check_display_invite_btn()
   {

      $_this = new self();

      $result = false;

      $user_id = $_this->user_id;

      $store_info = Store_logic::get_store_info_logic( $user_id );

      $store_id = array();

      foreach ($store_info as $row) 
      {

         $store_id[] = $row->id;

      }

      $data = Admin_user::check_display_invite_btn( $store_id );

      $result = $data->isNotEmpty() === true ? true : false ;

      return $result;

   }


   // 組合列表資料

   public static function user_list_data_bind( $OriData )
   {

      $_this = new self();

      $txt = Web_cht::get_txt();

      $result = array(
                     "title" => array(
                                 $txt['account'],
                                 $txt['real_name'],
                                 $txt['telephone'],
                                 $txt['auth'],
                                 $txt['status'],
                                 $txt['deadline'],
                                 $txt['action']
                              ),
                     "data" => array()
                 );

      if ( !empty($OriData) && $OriData->isNotEmpty() ) 
      {

         foreach ($OriData as $row) 
         {
   
            if ( is_object($row) ) 
            {

               $data = array(
                        "data" => array(
                                    "account"               => $row->account,
                                    "real_name"             => $row->real_name,
                                    "telephone"             => $row->mobile,
                                    "auth"                  => $row->auth ,
                                    "status"                => $row->status_txt,
                                    "deadline"              => $row->deadline,
                                 ),
                        "Editlink"  => "/user/" . $row->id . "/edit?",
                        "ExtendBtn" => true
                     );
               
            }

            $result["data"][] = $data;
         
         }


      }

      return $result;

   }


   public static function get_buy_spec_data( $buy_spec_data )
   {

      $_this = new self();

      $result = array();

      $txt = Web_cht::get_txt();

      if ( !empty($buy_spec_data) && is_array($buy_spec_data) ) 
      {

         foreach ($buy_spec_data as $index => $spec_data) 
         {

            $result[$index] = $spec_data.$txt["buy_date_spec_desc"].date("Y-m-d", strtotime("+".$spec_data." days"));

         }

      }

      return $result;      

   }


   // 取得輸入邏輯陣列

   public static function get_admin_input_template_array()
   {

      $_this = new self();

      $txt = Web_cht::get_txt();

      // 計算子帳數量

      $account_status = $_this->cnt_child_account();

      $buy_spec_data = $_this->get_buy_spec_data( $account_status["buy_spec_data"] );

      $deadline = !empty($account_status['free']) ? date("Y-m-d", strtotime("+30 days")) : $buy_spec_data ;

      $role_list = Role_logic::get_active_role();

      $role_list = Role_logic::filter_admin_role($role_list);

      $htmlData = array(
                     "account" => array(
                         "type"          => 1, 
                         "title"         => $txt["account"],
                         "key"           => "account",
                         "value"         => "" ,
                         "display"       => true,
                         "desc"          => "",
                         "attrClass"     => "",
                         "hasPlugin"     => "",
                         "placeholder"   => $txt["account_input"]
                     ),
                     "password" => array(
                         "type"          => 7, 
                         "title"         => $txt["password"],
                         "key"           => "password",
                         "value"         => "",
                         "display"       => true,
                         "desc"          => "",
                         "attrClass"     => "",
                         "hasPlugin"     => "",
                         "placeholder"   => $txt["password_input"],
                         "required"      => false
                     ),
                     "real_name" => array(
                         "type"          => 1, 
                         "title"         => $txt["real_name"],
                         "key"           => "real_name",
                         "value"         => "",
                         "data"          => "",
                         "display"       => true,
                         "desc"          => "",
                         "EventFunc"     => "",
                         "attrClass"     => "",
                         "hasPlugin"     => "",
                         "placeholder"   => $txt["real_name_input"]
                     ),
                     "telephone" => array(
                         "type"          => 1, 
                         "title"         => $txt["telephone"],
                         "key"           => "mobile",
                         "value"         => "",
                         "display"       => true,
                         "desc"          => "",
                         "attrClass"     => "",
                         "hasPlugin"     => "",
                         "placeholder"   => $txt["mobile_input"]
                     ),
                     "auth" => array(
                         "type"          => 6, 
                         "title"         => $txt["auth"],
                         "key"           => "auth[]",
                         "value"         => "",
                         "data"          => $role_list,
                         "display"       => true,
                         "desc"          => "",
                         "attrClass"     => "",
                         "hasPlugin"     => ""
                     ),
                     "status" => array(
                         "type"          => 3,
                         "title"         => $txt["status"],
                         "key"           => "active",
                         "value"         => "",
                         "data"          => array(
                                             1 => $txt["enable"],
                                             2 => $txt["disable"]
                                          ),
                         "display"       => true,
                         "desc"          => "",
                         "attrClass"     => "",
                         "hasPlugin"     => ""
                     ),
                     "deadline" => array(
                         "type"          => 2, 
                         "title"         => $txt["deadline"],
                         "key"           => "date_spec",
                         "value"         => "",
                         "data"          => $deadline,
                         "display"       => is_array($deadline) ? true : false,
                         "desc"          => "",
                         "attrClass"     => "",
                         "hasPlugin"     => ""
                     ),
                     "deadline2" => array(
                         "type"          => 5, 
                         "title"         => $txt["deadline"],
                         "key"           => "",
                         "value"         => is_string($deadline) ? $txt['free_date_spec_desc'] . "" . $deadline : "",
                         "data"          => "",
                         "display"       => !is_array($deadline) ? true : false,
                         "desc"          => "",
                         "attrClass"     => "",
                         "hasPlugin"     => ""
                     )
                 );

      return $htmlData;

   }


   // 組合資料

   public static function admin_input_data_bind( $htmlData, $OriData )
   {

      $_this = new self();

      $result = $htmlData;

      if ( !empty($OriData) && is_array($OriData) ) 
      {

         foreach ($htmlData as &$row) 
         {
         
            if ( is_array($row) ) 
            {

               $row["value"] = isset($OriData[$row["key"]]) ? $OriData[$row["key"]] : "" ;
               
            }

         }

         // 修正時改為純文字

         $htmlData["account"]["type"] = 5;

         // password 不顯示

         $htmlData["password"]["value"] = "";

         // 角色

         $id = isset($OriData["id"]) ? (int)$OriData["id"] : 0 ;

         $user_role = $id > 0 ? Admin_user_logic::get_user_role_by_id( $id ) : "" ;

         $user_role = $_this->get_object_or_array_key( $user_role );

         $htmlData["auth"]["value"] = $user_role;

         // 狀態

         $htmlData["status"]["value"] = $OriData["status"];

         // 到期日期

         $htmlData["deadline2"]["value"] = is_string($htmlData["deadline"]["data"]) ? $htmlData["deadline"]["data"] : "" ;

      }

      return $htmlData;

   }

}