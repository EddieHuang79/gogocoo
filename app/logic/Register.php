<?php

namespace App\logic;

use App\logic\Admin_user_logic;
use App\logic\Verifycode;
use Illuminate\Support\Facades\Session;

class register extends Basetool
{

   // 註冊格式

   public static function register_format( $data )
   {
         
         $_this = new self();

         $result = array();

         if ( !empty($data) && is_array($data) ) 
         {

            $result = array(
                           "account"            => isset($data["StoreEmail"]) && !empty($data["StoreEmail"]) ? trim($data["StoreEmail"]) : "",
                           "password"           => isset($data["StorePassword"]) && !empty($data["StorePassword"]) ? $_this->strFilter($data["StorePassword"]) : "",
                           "re_check_pwd"       => isset($data["ReCheckPassword"]) && !empty($data["ReCheckPassword"]) ? $_this->strFilter($data["ReCheckPassword"]) : "",
                           "mobile"             => isset($data["Mobile"]) && !empty($data["Mobile"]) ? $_this->strFilter($data["Mobile"]) : "",
                           "real_name"          => isset($data["RealName"]) && !empty($data["RealName"]) ? $_this->strFilter($data["RealName"]) : "",
                           "StoreName"          => isset($data["StoreName"]) && !empty($data["StoreName"]) ? $_this->strFilter($data["StoreName"]) : "",
                           "store_type_id"      => isset($data["store_type_id"]) && !empty($data["store_type_id"]) ? intval($data["store_type_id"]) : "",
                           "StoreCode"          => isset($data["StoreCode"]) && !empty($data["StoreCode"]) ? strtoupper($_this->strFilter($data["StoreCode"])) : "",
                           "verify_code"        => isset($data["verify"]) && !empty($data["verify"]) ? intval($data["verify"]) : 0,
                           "token"              => isset($data["_token"]) && !empty($data["_token"]) ? $_this->strFilter($data["_token"]) : "",
                           "social_register"    => isset($data["social_register"]) && !empty($data["social_register"]) ? intval($data["social_register"]) : 0,
                           "active"             => 1
                        );

         }

         return $result;

   }


   // 註冊驗證

   public static function register_process( $data )
   {

         $_this = new self();

         $txt = Web_cht::get_txt();
         
         $result = json_encode( array(0) );

         $error_msg = array();

         Session::forget('ErrorMsg');

         if ( !empty($data) && is_array($data) ) 
         {

            try{

               // 帳號: 檢查格式、帳號重複

               $user_id = Admin_user_logic::get_user_id( $data );

               if (!empty($user_id))
               {

                  $error_msg[] = $txt["accont_repeat_fail"];
               
               }

               if ( !$_this->is_email( $data["account"] ) ) 
               {
                  $error_msg[] = $txt["account_format_fail"];
               }

               

               // 密碼(英數字8-12碼)、密碼與確認密碼是否相同

               if ( $data["social_register"] <= 0 && !$_this->string_length( $data["password"] ) ) 
               {
                  $error_msg[] = $txt["pwd_length_fail"];
               }

               if ( $data["social_register"] <= 0 && !$_this->pwd_complex( $data["password"] ) ) 
               {
                  $error_msg[] = $txt["pwd_format_fail"];
               }

               if ( $data["social_register"] <= 0 && empty($data["parents_id"]) && $data["password"] != $data["re_check_pwd"] ) 
               {
                  $error_msg[] = $txt["pwd_recheck_fail"];
               }


               // 手機

               if ( !$_this->is_phone( $data["mobile"] ) ) 
               {
                  $error_msg[] = $txt["phone_format_fail"];
               }


               // 姓名

               if ( !$_this->strFilter( $data["real_name"] ) ) 
               {
                  $error_msg[] = $txt["real_name_fail"];
               }


               // 驗證碼

               if ( empty($data["parents_id"]) ) 
               {

                  // 店名

                  if ( !$_this->strFilter( $data["StoreName"] ) ) 
                  {
                     $error_msg[] = $txt["store_name_fail"];
                  }


                  // 行業別

                  if ( intval( $data["store_type_id"] ) < 1 ) 
                  {
                     $error_msg[] = $txt["store_type_fail"];
                  }


                  // 商家代號產生

                  if ( !empty( $data["StoreCode"] ) && Admin_user_logic::check_store_code_repeat( $data["StoreCode"] ) > 0 )
                  {
                     $error_msg[] = $txt["Store_code_fail"];
                  }


                  $compare = Verifycode::auth_verify_code( $data["verify_code"] );
         
                  if (!$compare) 
                  {
                     $error_msg[] = $txt["verify_code_error"];
                  } 

               }

               // 錯誤處理

               if (!empty($error_msg)) 
               {
                  throw new \Exception(json_encode($error_msg));
               }

               $result = "";

            }
            catch(\Exception $e)
            {

               $result = $_this->show_error_to_user( json_decode($e->getMessage() ,true) );

            }

         }

         return $result;

   }


   // 社交軟體寫入格式

   public static function social_format( $data )
   {
         
         $_this = new self();

         $result = array();

         if ( !empty($data) && is_array($data) ) 
         {

            $user_data = json_decode(stripcslashes($data["param"]), true);

            switch ($data["way"]) 
            {

               case 1:

                        $email = $user_data["id"] . "@facebook.com";

                        $result = array(
                                       "social"    => 1,
                                       "way"       => isset($data["way"]) && !empty($data["way"]) ? intval($data["way"]) : "",
                                       "account"   => $email,
                                       "email"     => $email,
                                       "pwd"       => isset($user_data["id"]) && !empty($user_data["id"]) ? $_this->strFilter($user_data["id"]) : "",
                                       "_token"    => csrf_token(),
                                    );
                  break;

               case 2:
                        $result = array(
                                       "social"    => 1,
                                       "way"       => isset($data["way"]) && !empty($data["way"]) ? intval($data["way"]) : "",
                                       "account"   => isset($user_data["email"]) && !empty($user_data["email"]) ? trim($user_data["email"]) : "",
                                       "email"     => isset($user_data["email"]) && !empty($user_data["email"]) ? trim($user_data["email"]) : "",
                                       "pwd"       => isset($user_data["id"]) && !empty($user_data["id"]) ? $_this->strFilter($user_data["id"]) : "",
                                       "_token"    => csrf_token(),
                                    );
                  break;
            
            }

         }

         return $result;

   }


}