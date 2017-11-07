<?php

namespace App\logic;

use App\model\Store;
use Illuminate\Support\Facades\Session;
use App\logic\Option_logic;
use App\logic\Admin_user_logic;
use App\logic\Shop_logic;

class Store_logic extends Basetool
{

	protected $user_id;

	public function __construct()
	{

		$Login_user = Session::get('Login_user');

		// user_id

		$this->user_id = $Login_user["user_id"];

	}


	// 計算店舖數

	public static function get_store_cnt()
	{

		$_this = new self();

		// 已有的店鋪數

		$store_cnt = Store::get_store_cnt( $_this->user_id );

		// 已購買的店鋪數

		$buy_store_cnt = Shop_logic::get_count_by_action_key( "create_shop" );

		$buy_store_cnt = array_shift($buy_store_cnt);

		// 免費帳號可以有2間的額度

		$free_store = 2 - $store_cnt ;

		// 剩餘額度

		$left = $free_store + $buy_store_cnt["count"];

		// return

		$result = array(
						"free" 					=> $free_store > 0 ? $free_store : 0,
						"buy" 					=> $buy_store_cnt["count"],
						"buy_spec_data"      	=> $buy_store_cnt["data"],
						"left" 					=> $left
					);


		return $result;

	}	


	// 取得店鋪列表

	public static function get_store_info( $data = array() )
	{

		$store_type_array = array();

		$store_type_name = array();
		
		$user_id = array();

		$result = array();

		$_this = new self();


		// 已有的店鋪數

		$store_info = Store::get_store_info( $_this->user_id );


		if ( $store_info->count() > 0 ) 
		{

			foreach ($store_info as $row) 
			{

				if ( is_object($row) ) 
				{

					$store_type_array[] = $row->store_type;
					
					$user_id[] = $row->id;

				}

			}


			// 取得行業別

			$store_type = Option_logic::get_store_type_name( $store_type_array );

			foreach ($store_type as $row) 
			{

				$store_type_name[$row->id] = $row->key . " - " . $row->value;
			
			}


			// 計算店鋪截止日

			$cnt_deadline = Shop_logic::count_deadline( $user_id, "create_shop" );


			// 寫入名稱

			foreach ($store_info as &$row) 
			{

				if ( is_object($row) ) 
				{

					$row->deadline = Shop_logic::get_deadline( $row, $cnt_deadline );

					$row->store_type_name = isset($store_type_name[$row->store_type]) ? $store_type_name[$row->store_type] : "" ;

				}
			
			}

			if ( !empty($data["store_id"]) ) 
			{

				foreach ($store_info as $row) 
				{
			
					if ( is_object($row) && $row->id == $data["store_id"] ) 
					{
			
						$result = $row;
			
					}
			
				}
			
			}
			else
			{

				$result = $store_info;

			}

		}

		return $result;

	}


	// 新增格式

	public static function insert_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{
		     
			$result = array(
			            "store_name"        => isset($data["StoreName"]) ? $_this->strFilter($data["StoreName"]) : "",
			            "store_code"        => isset($data["StoreCode"]) && !empty($data["StoreCode"]) ? strtoupper($_this->strFilter($data["StoreCode"])) : Admin_user_logic::get_rand_string(),
			            "store_type"     	=> isset($data["store_type_id"]) ? intval($data["store_type_id"]) : "",
			            "user_id"     		=> isset($_this->user_id) ? intval($_this->user_id) : "",
			            "is_free"    		=> isset($data["is_free"]) ? intval($data["is_free"]) : 1,
			            "created_at"    	=> date("Y-m-d H:i:s"),
			            "updated_at"    	=> date("Y-m-d H:i:s")
			         );

		}

		return $result;

	}

	// 更新格式

	public static function update_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
			            "store_name"        => isset($data["StoreName"]) ? $_this->strFilter($data["StoreName"]) : "",
			            "store_code"        => isset($data["StoreCode"]) && !empty($data["StoreCode"]) ? strtoupper($_this->strFilter($data["StoreCode"])) : Admin_user_logic::get_rand_string(),
			            "updated_at"    	=> date("Y-m-d H:i:s")
			         );

		}

		return $result;

	}


	// 修改店鋪

	public static function edit_store( $data, $store_id )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($store_id) && is_int($store_id) ) 
		{

			$result = Store::edit_store( $data, $store_id );

		}

		return $result;

	}


	// 新增店鋪

	public static function add_store( $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) ) 
		{

			$result = Store::add_store( $data );

		}

		return $result;

	}


	// 取得店鋪

	public static function get_single_store( $store_id )
	{

		$result = false;

		if ( !empty($store_id) && is_int($store_id) ) 
		{

			$result = Store::get_single_store( $store_id );

		}

		return $result;

	}	


	// 檢查 store user

	public static function check_store_user( $store_id )
	{

		$_this = new self();

		$result = false;

		$store_id = intval($store_id);

		if ( !empty($store_id) && is_int($store_id) ) 
		{

			$data = Store::get_single_store( $store_id );

			$parents_id = Admin_user_logic::get_parents_id( array("user_id" => $_this->user_id ) );

			$parents_id = $parents_id == 0 ? $_this->user_id : $parents_id ;

			$result = $parents_id == $data->user_id ? true : false;

		}

		return $result;

	}	


	// 切換店舖

	public static function change_store( $store_id )
	{

		$_this = new self();

		$result = false;

		$store_id = intval($store_id);

		if ( !empty($store_id) && is_int($store_id) ) 
		{

			Session::put( 'Store', $store_id );

			$result = true;

		}

		return $result;

	}	


	// 擴展店鋪日期

	public static function extend_store_deadline( $user_id, $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($user_id) && is_int($user_id) ) 
		{

			$result = Shop_logic::add_use_record( (int)$user_id, $data, $type = 2 );

		}

	  	return $result;
	     
	}


   	// 以代碼取得店鋪id

	public static function get_store_id_by_code( $store_code )
	{
	    
		$result = !empty($store_code) ? Store::get_store_id_by_code( $store_code ) : false ;

		return $result;

	}	


	// 店鋪驗證

	public static function store_verify( $data )
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$result = array();

		$ErrorMsg = array();

		if ( !empty($data) && is_array($data) ) 
		{

			try 
			{

				// 店名

				if ( !$_this->strFilter( $data["StoreName"] ) ) 
				{

					$ErrorMsg[] = $txt["store_name_fail"];

				}


				// 行業別

				if ( intval( $data["store_type_id"] ) < 1 ) 
				{

					$ErrorMsg[] = $txt["store_type_fail"];

				}


				// 商家代號產生

				if ( !empty( $data["StoreCode"] ) && Admin_user_logic::check_store_code_repeat( $data["StoreCode"] ) > 0 )
				{

					$ErrorMsg[] = $txt["Store_code_fail"];

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


	// 取得所有關連店鋪

	public static function get_rel_shop_id( $shop_id )
	{

		$result = array();

		if ( !empty($shop_id) && is_int($shop_id) ) 
		{

			$store_data = Store::get_single_store( $shop_id );

			$rel_user_id = Admin_user_logic::get_rel_user_id( $store_data->user_id );

			$data = Store::get_rel_shop_id( $rel_user_id );

			foreach ($data as $row) 
			{

				$result[] = $row->id;
			
			}

		}

		return $result;

	}




}
