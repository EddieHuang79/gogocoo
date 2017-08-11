<?php

namespace App\logic;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class Redis_tool
{

	protected $Search_tool_key = "search_tool_";

	protected $User_role_key = "User_role_";

	protected $Menu_key = "menu_data_";

	protected $Active_role_key = "Active_role";

	protected $msg_key = "msg_";
    
	public static function set_search_tool( $data )
	{

		$_this = new self;

		$service_id = Session::get('service_id');

		$Search_tool_key = $_this->Search_tool_key.$service_id;

		empty(Redis::get( $Search_tool_key )) ? Redis::set( $Search_tool_key, json_encode($data) ) : "" ;

	}

	public static function get_search_tool()
	{

		$_this = new self;

		$service_id = Session::get('service_id');

		if (!empty($service_id)) 
		{
			$Search_tool_key = $_this->Search_tool_key.$service_id;

			$data = Redis::get( $Search_tool_key );
		}

		$result = !empty($data) ? json_decode($data, true) : array() ;

		return $result;

	}

	public static function set_user_role( $data, $user_id )
	{

		$_this = new self;

		$User_role_key = $_this->User_role_key.$user_id;

		empty(Redis::get( $User_role_key )) ? Redis::set( $User_role_key, json_encode($data) ) : "" ;

	}

	public static function get_user_role( $id )
	{

		$_this = new self;

		$User_role_key = $_this->User_role_key.$id;

		$data = Redis::get($User_role_key);

		$result = !empty($data) ? json_decode($data) : array() ;

		return $result;

	}

	public static function del_user_role( $user_id )
	{

		$_this = new self;

		$User_role_key = $_this->User_role_key.$user_id;
		
		Redis::del( $User_role_key );

	}

	public static function set_menu_data( $key, $data )
	{

		$_this = new self;

		$Menu_data_key = $_this->Menu_key.$key;

		empty(Redis::get( $Menu_data_key )) ? Redis::set( $Menu_data_key, json_encode($data) ) : "" ;

	}

	public static function get_menu_data( $key )
	{

		$_this = new self;

		$Menu_data_key = $_this->Menu_key.$key;

		$data = Redis::get( $Menu_data_key );

		return $data;

	}

	public static function del_menu_data_all()
	{

		$_this = new self;

		$Menu_data_key = $_this->Menu_key."*";

		$match_key = Redis::KEYS( $Menu_data_key );

		foreach ($match_key as $del_key) 
		{
			Redis::del( $del_key );
		}
		
	}

	public static function set_active_role( $data )
	{

		$_this = new self;

		$Active_role_key = $_this->Active_role_key;

		Redis::set( $Active_role_key, json_encode($data) );

	}

	public static function get_active_role()
	{

		$_this = new self;

		$Active_role_key = $_this->Active_role_key;

		$data = Redis::get( $Active_role_key );

		$data = json_decode($data);

		return $data;

	}

	public static function set_read_msg( $user_id, $msg_id )
	{

		$_this = new self;

		$msg_key = $_this->msg_key . $user_id;

		$data = Redis::get( $msg_key );

		$data = isset($data) ? json_decode($data, true) : array() ;

		if (!in_array($msg_id, $data)) 
		{
			$data[] = $msg_id;
		}

		Redis::set( $msg_key, json_encode($data) );

	}

	public static function get_read_msg( $user_id )
	{

		$_this = new self;

		$msg_key = $_this->msg_key . $user_id;

		$data = Redis::get( $msg_key );

		$data = isset($data) ? json_decode($data, true) : array() ;

		return $data;

	}

}
