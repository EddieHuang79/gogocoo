<?php

namespace App\logic;

use App\model\Store;
use Illuminate\Support\Facades\Session;
use App\logic\Option_logic;
use App\logic\Admin_user_logic;

class Store_logic extends Basetool
{

	protected $user_id;

	public function __construct()
	{

		$Login_user = Session::get('Login_user');

		// user_id

		$this->user_id = $Login_user["user_id"];

	}

	public static function get_store_cnt()
	{

		$_this = new self();

		// 已有的店鋪數

		$store_cnt = Store::get_store_cnt( $_this->user_id );


		// 已購買的店鋪數

		$buy_store_cnt = 0;


		// 免費帳號可以有2間的額度

		$create_status = 2 - $store_cnt + $buy_store_cnt;

		// return
		$result = array(
						"free" 	=> $buy_store_cnt == 0 && $create_status >= 1 && $store_cnt < 2 ? 1 : 0,
						"buy" 	=> $buy_store_cnt
					);


		return $result;

	}	

	public static function get_store_info( $data = array() )
	{

		$store_type_array = array();
		$store_type_name = array();


		$_this = new self();


		// 已有的店鋪數

		$store_info = Store::get_store_info( $_this->user_id );

		foreach ($store_info as $row) 
		{
			$store_type_array[] = $row->store_type;
		}


		// 取得行業別

		$store_type = Option_logic::get_store_type_name( $store_type_array );

		foreach ($store_type as $row) 
		{
			$store_type_name[$row->id] = $row->key . " - " . $row->value;
		}


		// 寫入名稱

		foreach ($store_info as &$row) 
		{
			$row->store_type_name = isset($store_type_name[$row->store_type]) ? $store_type_name[$row->store_type] : "" ;
		}


		$result = $store_info;

		if ( !empty($data["store_id"]) ) 
		{
			foreach ($store_info as $row) 
			{
				if ( $row->id == $data["store_id"] ) 
				{
					$result = $row;
				}
			}
		}

		return $result;

	}	

	// 新增格式
	public static function insert_format( $data )
	{

		$_this = new self();

		// 取得帳號
	     
		$result = array(
		            "store_name"        => isset($data["StoreName"]) ? $_this->strFilter($data["StoreName"]) : "",
		            "store_code"        => isset($data["StoreCode"]) && !empty($data["StoreCode"]) ? strtoupper($_this->strFilter($data["StoreCode"])) : Admin_user_logic::get_rand_string(),
		            "store_type"     	=> isset($data["store_type_id"]) ? intval($data["store_type_id"]) : "",
		            "user_id"     		=> isset($_this->user_id) ? intval($_this->user_id) : "",
		            "created_at"    	=> date("Y-m-d H:i:s"),
		            "updated_at"    	=> date("Y-m-d H:i:s")
		         );


		return $result;

	}

	// 更新格式
	public static function update_format( $data )
	{

		$_this = new self();

		$result = array(
		            "store_name"        => isset($data["StoreName"]) ? $_this->strFilter($data["StoreName"]) : "",
		            "store_code"        => isset($data["StoreCode"]) && !empty($data["StoreCode"]) ? strtoupper($_this->strFilter($data["StoreCode"])) : Admin_user_logic::get_rand_string(),
		            "updated_at"    	=> date("Y-m-d H:i:s")
		         );

		return $result;

	}

	// 修改店鋪
	public static function edit_store( $data, $store_id )
	{

		return Store::edit_store( $data, $store_id );

	}

	// 新增店鋪
	public static function add_store( $data )
	{

		return Store::add_store( $data );

	}

	// 取得店鋪
	public static function get_single_store( $store_id )
	{

		$data = Store::get_single_store( $store_id );

		return $data;

	}	

	// 檢查 store user
	public static function check_store_user( $store_id )
	{

		$_this = new self();

		$store_id = intval($store_id);

		$data = Store::get_single_store( $store_id );

		$parents_id = Admin_user_logic::get_parents_id( array("user_id" => $_this->user_id ) );

		$parents_id = $parents_id == 0 ? $_this->user_id : $parents_id ;

		$result = $parents_id == $data->user_id ? true : false;

		return $result;

	}	

	// 檢查 store user
	public static function change_store( $store_id )
	{

		$_this = new self();

		$store_id = intval($store_id);

		Session::put( 'Store', $store_id );

	}	


}
