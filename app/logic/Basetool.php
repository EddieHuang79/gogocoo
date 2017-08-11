<?php

namespace App\logic;


abstract class Basetool
{
    public function strFilter( $str = '' )
    {

    	$str = trim($str);
		
		$result = preg_replace("/[ '.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/", "", $str);

		return $result;

    }

    public function get_object_or_array_key( $data )
    {

    	$result = array();

    	foreach ($data as $key => $value) 
    	{
    		$result[] = intval($key);
    	}

    	return $result;

    }

    public function is_email( $email )
    {

       return filter_var($email, FILTER_VALIDATE_EMAIL);

    }

    public function is_phone( $phone )
    {

        return preg_match("/^09[0-9]{8}/", $phone);
    
    }

    public function string_length( $pwd , $MinLen = 8, $MaxLen = 12)
    {

        return strlen($pwd) >= $MinLen && strlen($pwd) <= $MaxLen ;

    }

    public function pwd_complex( $pwd )
    {

        $result = preg_match("/\w{8,12}/", $pwd) && preg_match("/\d/", $pwd) && preg_match("/[a-z]/", $pwd) && preg_match("/[A-Z]/", $pwd) ? true : false;

        return $result;

    }

    public function show_error_to_user( $error )
    {

        $result = array();
        
        foreach ($error as $key => $value) 
        {
            $item = $key + 1;
            $result[]= $item . ". " . $value;
        }

        return $result;

    }

    public function made_key( $data )
    {

        $result = implode("-", $data);

        return $result;

    }    

}