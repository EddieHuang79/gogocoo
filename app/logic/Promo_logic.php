<?php

namespace App\logic;

use App\model\Promo;

class Promo_logic extends Basetool
{

	protected $status_txt = array();


	public function __construct()
	{

		$txt = Web_cht::get_txt();

		$this->status_txt = array(
								1 	=>	$txt["enable"],
								2 	=>	$txt["disable"]
							);

	}	


	// 新增格式

	public static function insert_format( $data )
	{
	     
		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
							"mall_shop_id"      => isset($data["mall_shop_id"]) ? intval($data["mall_shop_id"]) : "",
							"status"          	=> isset($data["status"]) ? intval($data["status"]) : "",
							"cost"         		=> isset($data["cost"]) ? intval($data["cost"]) : "",
							"start_date"       	=> isset($data["start_date"]) ? date("Y-m-d 00:00:00", strtotime($data["start_date"])) : "",
							"end_date"         	=> isset($data["end_date"]) ? date("Y-m-d 23:59:59", strtotime($data["end_date"])) : "",
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
							"mall_shop_id"      => isset($data["mall_shop_id"]) ? intval($data["mall_shop_id"]) : "",
							"status"          	=> isset($data["status"]) ? intval($data["status"]) : "",
							"cost"         		=> isset($data["cost"]) ? intval($data["cost"]) : "",
							"start_date"       	=> isset($data["start_date"]) ? date("Y-m-d 00:00:00", strtotime($data["start_date"])) : "",
							"end_date"         	=> isset($data["end_date"]) ? date("Y-m-d 23:59:59", strtotime($data["end_date"])) : "",
							"updated_at"    => date("Y-m-d H:i:s")
			            );

		}

		return $result;

	}


	// 新增促銷價

	public static function add_promo_data( $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($data['mall_shop_id']) ) 
		{

			$result = Promo::add_promo_data( $data );

		}

		return $result;

	}


	// 修改促銷價

	public static function edit_promo_data( $data, $promo_id )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($promo_id) && is_int($promo_id) ) 
		{

			Promo::edit_promo_data( $data, $promo_id );

			$result = true;

		}

		return $result;

	}


	// 取得促銷價

	public static function get_promo_price( $mall_shop_id )
	{

		$_this = new self();

		$result = array();

		$status_txt = $_this->status_txt;

		if ( !empty($mall_shop_id) && is_int($mall_shop_id) ) 
		{

			$data = Promo::get_promo_price( $mall_shop_id );

			if ( $data->isNotEmpty() ) 
			{

				foreach ($data as $row) 
				{
				
					$result[] = array(
									"id" 			=> $row->id,
									"cost" 			=> $row->cost,
									"start_date" 	=> $row->start_date,
									"end_date" 		=> $row->end_date,
									"status" 		=> $row->status,
									"status_txt" 	=> isset($status_txt[$row->status]) ? $status_txt[$row->status] : "" ,
 								);

				}
			
			}

		}

		return $result;

	}


	// 取得單筆資料

	public static function get_single_promo_data( $id )
	{

		$result = array();

		if ( !empty($id) && is_int($id) ) 
		{

			$result = Promo::get_single_promo_data( $id );

		}

		return $result;

	}


	// 判斷日期是否重複

	public static function is_promo_date_repeat( $data, $except_id = 0 )
	{

		$result = false;

		if ( !empty($data) && $data["status"] === 1 ) 
		{

			$data = Promo::is_promo_date_repeat( $data, $except_id );

			$result = $data->isNotEmpty() ? true : false ;

		}

		return $result;

	}

	// 取得促銷價

	public static function get_active_promo_price( $mall_shop_id )
	{

		$_this = new self();

		$result = 0;

		if ( !empty($mall_shop_id) && is_int($mall_shop_id) ) 
		{

			$data = Promo::get_active_promo_price( $mall_shop_id );

			$result = !empty($data) ? $data->cost : 0 ;

		}

		return $result;

	}

}
