<?php

namespace App\logic;

use App\model\Option;

class Option_logic extends Basetool
{


	// 取得店鋪資料

	public static function get_store_data()
	{

		$result = array();

		$child = array();

		$data = Option::get_store_data();

		foreach ($data as $key => $row) 
		{

			if ( $row->parents_id == 0 ) 
			{
				$result[$row->id] = array(
											"name" => $row->key . " - " . $row->value
										);
			}

			if ( $row->parents_id != 0 ) 
			{
				$child[$row->parents_id][$row->id] = array(
															"name" => $row->key . " - " . $row->value
														);
			}

		}


		foreach ($result as $key => &$value) 
		{

			$value["data"] = $child[$key];
		
		}

		return $result;

	}


	// 取得店鋪分類名稱

	public static function get_store_type_name( $store_type )
	{

		$result = !empty($store_type) && is_array($store_type) ? Option::get_store_type_by_id( $store_type ) : false ;

		return $result;

	}


	// 取得入庫分類

	public static function get_in_warehouse_category()
	{

		$_this = new self();

		$data = $_this->get_option( array( "_in_warehouse_category_option" ) );

		$result = json_decode($data[0]->value);

		return $result;

	}


	// 取得出庫分類

	public static function get_out_warehouse_category()
	{

		$_this = new self();

		$data = $_this->get_option( array( "_out_warehouse_category_option" ) );

		$result = json_decode($data[0]->value);

		return $result;

	}


	// 取得指定選項

	public static function get_option( $data )
	{

		$result = !empty($data) ? Option::get_option( $data ) : array();

		return $result;

	}


	// 取得選單

	public static function get_select_option( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			foreach ($data as $key => $value) 
			{

				$option_key = "_".$value['name'];

				$option_data = $_this->get_option( array( $option_key ) );

				if ($option_data->count() > 0) 
				{
					
					foreach ($option_data as &$row) 
					{
						
						$row->value = json_decode($row->value, true);

					}

					$result[$value['name']] = $option_data[0]->value;

				}

			}

		}

		return $result;

	}


}
