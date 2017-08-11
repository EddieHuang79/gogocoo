<?php

namespace App\logic;

use App\logic\Admin_user_logic;

class ForgotPassword extends Basetool
{

   public static function password_reset_format( $data )
   {
         
		$_this = new self();

		$result = array(
		            "account"       => isset($data["email"]) && !empty($data["email"]) ? trim($data["email"]) : "",
		            "verify_code"   => isset($data["verify"]) && !empty($data["verify"]) ? intval($data["verify"]) : 0,
		            "token"         => isset($data["_token"]) && !empty($data["_token"]) ? $_this->strFilter($data["_token"]) : "",
		            "created_at"    => date("Y-m-d H:i:s")
		         );

		return $result;

   }

   public static function password_reset_verify( $data )
   {

   		$_this = new self();

   		$error_msg = array();

   		$txt = Web_cht::get_txt();

   		$result = "";

		try{

			// 驗證碼

			$compare = Verifycode::auth_verify_code( $data["verify_code"] );

			if (!$compare) 
			{

			   $error_msg[] = $txt["verify_code_error"];
			   
			} 

            // 更新db 
            
            $user_id = Admin_user_logic::get_user_id( $data );
            
			if (empty($user_id)) 
			{

			   $error_msg[] = $txt["accont_error"];
			   
			}

			// 錯誤處理

			if (!empty($error_msg)) 
			{

			   throw new \Exception(json_encode($error_msg));

			}

			
            // 產生密碼

            $new_password = Admin_user_logic::get_rand_string( 12 );
            $new_password_hash = array( "password" => bcrypt( $new_password ));


			// 更新資料庫

			Admin_user_logic::edit_user( $new_password_hash, $user_id );

			

		}
		catch(\Exception $e)
		{

			$result = $_this->show_error_to_user( json_decode($e->getMessage() ,true) );

		}   	

		return $result;	

   }

}
