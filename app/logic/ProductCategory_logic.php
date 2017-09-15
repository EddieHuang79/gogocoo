<?php

namespace App\logic;

use Illuminate\Support\Facades\Session;
use App\model\ProductCategory;
use App\logic\Web_cht;

class ProductCategory_logic extends Basetool
{

	protected $txt;

	public function __construct()
	{

		// 文字
		$this->txt = Web_cht::get_txt();

	}

	// 主檔新增格式

	public static function insert_main_format( $data )
	{

		$_this = new self();

		$shop_id = Session::get( 'Store' );

		$result = array(
		            "shop_id"     		=> $shop_id,
		            "parents_id"     	=> isset($data["parents_category"]) ? intval($data["parents_category"]) : 0,
		            "name"				=> isset($data["name"]) ? $_this->strFilter($data["name"]) : ""
		         );

		return $result;

	}

	// 主檔更新格式

	public static function update_main_format( $data )
	{

		$_this = new self();

		$result = array(
		            "parents_id"     	=> isset($data["parents_category"]) ? intval($data["parents_category"]) : 0,
		            "name"				=> isset($data["name"]) ? $_this->strFilter($data["name"]) : ""
		         );

		return $result;

	}

	// 取得類別清單

	public static function get_product_category_data()
	{

		$_this = new self();

		$result = array();

		$txt = $_this->txt;

		$shop_id = Session::get( 'Store' );

		$parents_category = $_this->get_parents_category_list();

		$data = ProductCategory::get_product_category_data( $shop_id );

		foreach ($data as &$row) 
		{
		
			$row->parents_name = isset($parents_category[$row->parents_id]) ? $parents_category[$row->parents_id] : $txt['none'] ;

		}

		return $data;

	}

	// 取得所有類別清單

	public static function get_all_category_list()
	{

		$result = array();

		$shop_id = Session::get( 'Store' );

		$data = ProductCategory::get_product_category_data( $shop_id );

		foreach ($data as $row) 
		{

			$result[$row->id] = $row->name;

		}

		return $result;

	}

	// 取得父類別清單

	public static function get_parents_category_list( $excpet_id = 0 )
	{

		$result = array();

		$shop_id = Session::get( 'Store' );

		$data = ProductCategory::get_parents_category_list( $shop_id, $excpet_id );

		foreach ($data as $row) 
		{

			$result[$row->id] = $row->name;

		}

		return $result;

	}

	// 取得單一商品

	public static function get_single_product_category( $id )
	{

		$result = array();

		$data = ProductCategory::get_single_product_category( $id );

		return $data;

	}

	// 新增商品

	public static function add_product_category( $data )
	{

		ProductCategory::add_product_category( $data );

	}

	// 主檔更新

	public static function edit_product_category( $data, $product_category_id )
	{

		ProductCategory::edit_product_category( $data, $product_category_id );

	}

	// 取得該父類別子項

	public static function get_child_product_category( $parents_id )
	{

		$result = array();

		$data = ProductCategory::get_child_product_category( $parents_id );

		if ( $data->count() > 0 ) 
		{

			foreach ($data as $row) 
			{
				
				$result[$row->id] = $row->name; 

			}

		}

		return $result;

	}


	// 取得該父類別id

	public static function get_parents_category_id( $child_id )
	{

		$result = 0;

		$data = ProductCategory::get_parents_category_id( $child_id );

		if ( !empty($data) ) 
		{

			$result = $data->parents_id;

		}

		return $result;

	}

	// 取得該父類別id 多筆

	public static function get_mutli_parents_category_id( $child_id_array )
	{

		$result = array();

		$data = ProductCategory::get_mutli_parents_category_id( $child_id_array );

		if ( $data->count() > 0 ) 
		{

			foreach ($data as $key => $row) 
			{

				$result[$row->id] = $row->parents_id;
			
			}

		}

		return $result;

	}

	// 取得該父類別id 多筆

	public static function get_parents_id_name_trans()
	{

		$tmp = array();

		$result = array();

		$data = ProductCategory::get_all_parents_category();

		if ( $data->count() > 0 ) 
		{

			foreach ($data as $key => $row) 
			{

				$result[$row->id] = $row->name;
			
			}

		}

		return $result;

	}

}
