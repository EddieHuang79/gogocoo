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

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$shop_id = Session::get( 'Store' );

			$result = array(
			            "shop_id"     		=> $shop_id,
			            "parents_id"     	=> isset($data["parents_category"]) ? intval($data["parents_category"]) : 0,
			            "name"				=> isset($data["name"]) ? $_this->strFilter($data["name"]) : ""
			         );


		}

		return $result;

	}


	// 主檔更新格式

	public static function update_main_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
			            "parents_id"     	=> isset($data["parents_category"]) ? intval($data["parents_category"]) : 0,
			            "name"				=> isset($data["name"]) ? $_this->strFilter($data["name"]) : ""
			         );

		}

		return $result;

	}


	// 取得類別清單

	public static function get_product_category_data()
	{

		$_this = new self();

		$result = array();

		$txt = $_this->txt;

		$shop_id = Session::get( 'Store' );

		$rel_shop_id = Store_logic::get_rel_shop_id( $shop_id );

		$parents_category = $_this->get_parents_category_list();

		$data = ProductCategory::get_product_category_data( $rel_shop_id );

		if ( !empty($data) )
		{

			foreach ($data as &$row) 
			{
			
				if ( is_object($row) ) 
				{
				
					$row->parents_name = isset($parents_category[$row->parents_id]) ? $parents_category[$row->parents_id] : $txt['none'] ;
				
				}

			}

			$result = $data;

		}

		return $result;

	}


	// 取得所有類別清單

	public static function get_all_category_list()
	{

		$result = array();

		$shop_id = Session::get( 'Store' );

		$rel_shop_id = Store_logic::get_rel_shop_id( $shop_id );

		$data = ProductCategory::get_product_category_data( $rel_shop_id );

		if ( !empty($data) )
		{

			foreach ($data as $row) 
			{

				if ( is_object($row) ) 
				{

					$result[$row->id] = $row->name;

				}

			}

		}

		return $result;

	}


	// 取得父類別清單

	public static function get_parents_category_list( $excpet_id = 0 )
	{

		$result = array();

		$shop_id = Session::get( 'Store' );

		$rel_shop_id = Store_logic::get_rel_shop_id( $shop_id );

		$data = ProductCategory::get_parents_category_list( $rel_shop_id, $excpet_id );

		foreach ($data as $row) 
		{

			if ( is_object($row) ) 
			{

				$result[$row->id] = $row->name;

			}

		}

		return $result;

	}


	// 取得單一商品

	public static function get_single_product_category( $id )
	{

		$result = array();

		if ( !empty($id) && is_int($id) ) 
		{

			$result = ProductCategory::get_single_product_category( $id );

		}

		return $result;

	}


	// 新增商品

	public static function add_product_category( $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) ) 
		{
	
			ProductCategory::add_product_category( $data );

			$result = true;

		}

		return $result;

	}


	// 主檔更新

	public static function edit_product_category( $data, $product_category_id )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($product_category_id) && is_int($product_category_id) ) 
		{

			ProductCategory::edit_product_category( $data, $product_category_id );

			$result = true;

		}

		return $result;


	}


	// 取得該父類別子項

	public static function get_child_product_category( $parents_id )
	{

		$result = array();

		if ( !empty($parents_id) && is_int($parents_id) ) 
		{

			$data = ProductCategory::get_child_product_category( $parents_id );

			if ( $data->count() > 0 ) 
			{

				foreach ($data as $row) 
				{
					
					$result[$row->id] = $row->name; 

				}

			}

		}

		return $result;

	}


	// 取得該父類別id

	public static function get_parents_category_id( $child_id )
	{

		$result = false;

		if ( !empty($child_id) && is_int($child_id) ) 
		{

			$data = ProductCategory::get_parents_category_id( $child_id );

			$result = is_object($data) ? $data->parents_id : false ;

		}

		return $result;

	}


	// 取得該父類別id 多筆

	public static function get_mutli_parents_category_id( $child_id_array )
	{

		$result = array();

		if ( !empty($child_id_array) && is_array($child_id_array) ) 
		{

			$data = ProductCategory::get_mutli_parents_category_id( $child_id_array );

			if ( $data->count() > 0 ) 
			{

				foreach ($data as $key => $row) 
				{

					$result[$row->id] = $row->parents_id;
				
				}

			}

		}

		return $result;

	}


	// 取得父類別id 多筆

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
