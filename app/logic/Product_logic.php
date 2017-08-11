<?php

namespace App\logic;

use App\model\Product;
use Illuminate\Support\Facades\Session;
use App\logic\Web_cht;

class Product_logic extends Basetool
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

		$result = array(
		            "product_name"      => isset($data["product_name"]) ? $_this->strFilter($data["product_name"]) : "",
		            "safe_amount"       => isset($data["safe_amount"]) ? intval($data["safe_amount"]) : 0,
		            "created_at"    	=> date("Y-m-d H:i:s"),
		            "updated_at"    	=> date("Y-m-d H:i:s")
		         );

		return $result;

	}


	// 附屬資料新增格式

	public static function insert_extra_format( $data, $product_extra_column, $product_id )
	{

		$_this = new self();

		$filter_data = array();

		$shop_id = Session::get( 'Store' );		

		$result["shop_id"] = $shop_id;

		$result["product_id"] = $product_id;

		foreach ($product_extra_column as $row_data) 
		{

			switch ($row_data['name']) 
			{

				default:

					$default_value = $row_data['type'] == 'number' ? 0 : "" ;

					$filter_data[$row_data['name']] = isset($data[$row_data['name']]) && !empty($data[$row_data['name']]) ? trim($data[$row_data['name']]) : $default_value ;

					break;

			}

			$result[$row_data['name']] = $filter_data[$row_data['name']];

		}

		return $result;

	}

	// 主檔更新格式

	public static function update_main_format( $data )
	{

		$_this = new self();

		$result = array(
		            "product_name"      => isset($data["product_name"]) ? $_this->strFilter($data["product_name"]) : "",
		            "safe_amount"       => isset($data["safe_amount"]) ? intval($data["safe_amount"]) : 0,
		            "updated_at"    	=> date("Y-m-d H:i:s")
		         );

		return $result;

	}


	// 附屬資料更新格式

	public static function update_extra_format( $data, $product_extra_column )
	{

		$_this = new self();

		$filter_data = array();

		foreach ($product_extra_column as $row_data) 
		{

			switch ($row_data['name']) 
			{

				default:

					$default_value = $row_data['type'] == 'number' ? 0 : "" ;

					$filter_data[$row_data['name']] = isset($data[$row_data['name']]) && !empty($data[$row_data['name']]) ? trim($data[$row_data['name']]) : $default_value ;

					break;

			}

			$result[$row_data['name']] = $filter_data[$row_data['name']];

		}

		return $result;
	}


	// 規格資料判別，過濾更新的與新增

	public static function insert_or_update_spec_data( $type, $data, $product_id )
	{

		$_this = new self();

		$result = array();

		$spec_column = array();

		foreach ($data as $key => $row) 
		{

			if (is_array($row)) 
			{
			
				$spec_column[$key] = $row;
			
			}

		}

		$product_spec_column = $_this->get_product_spec_column();

		foreach ($product_spec_column as $row) 
		{ 

			foreach ($data[$row->name] as $key => $value) 
			{

				$spec_data[$key][$row->name] = $value;

			}

		}

		switch ($type) 
		{

			case 'insert':

					foreach ($spec_data as $key => $row) 
					{
						if ( !isset($data["spec_id"]) || ( isset($data["spec_id"]) && empty($data["spec_id"][$key]) && $data["spec_id"][$key] <= 0 ) ) 
						{
							$result[$key] = array(
									            "product_id"      	=> $product_id,
									            "spec_data"      	=> json_encode($row)
									         );
						}
					}

				break;
			
			case 'update':

					foreach ($spec_data as $key => $row) 
					{

						if ( !empty($data["spec_id"][$key]) && $data["spec_id"][$key] > 0 ) 
						{
							$result[$data["spec_id"][$key]] = array(
													            "spec_data"      	=> json_encode($row)
													         );

						}

					}

				break;

		}
		
		return $result;

	}


	// 新增商品

	public static function add_product( $data )
	{

		return product::add_product( $data );

	}

	// 新增商品額外資訊

	public static function add_extra_data( $data )
	{

		return product::add_extra_data( $data );

	}

	// 新增商品規格

	public static function add_product_spec_data( $data )
	{

		return product::add_product_spec_data( $data );

	}

	// 主檔更新

	public static function edit_product( $data, $product_id )
	{

		return product::edit_product( $data, $product_id );

	}


	// 附屬資料更新

	public static function edit_product_extra_data( $data, $product_id )
	{

		return product::edit_product_extra_data( $data, $product_id );

	}


	// 規格資料更新

	public static function edit_product_spec_data( $data )
	{

		return product::edit_product_spec_data( $data );

	}

	// 取得商品額外欄位

	public static function get_product_extra_column()
	{

		$result = array();

		$data = Product::get_product_extra_column();

		unset($data[0]);
		unset($data[1]);
		unset($data[2]);

		foreach ($data as $key => $row) 
		{
			$result[$key]["name"] = $row->Field;

			if ( strpos($row->Type, "varchar") !== false ) 
			{
				$result[$key]["type"] = "text";
			}

			if ( strpos($row->Type, "int") !== false ) 
			{
				$result[$key]["type"] = "number";
			}

		}

		return $result;

	}


	// 取得規格欄位

	public static function get_product_spec_column()
	{

		$result = array();

		$data = Product::get_product_spec_column();

		if ( $data->count() > 0 ) 
		{

			foreach ($data as $key => $row) 
			{

				$result[$key] = json_decode($row->value);

				foreach ($result[$key]->option as &$datarow) 
				{
					$datarow->name = $datarow->name_zh;
				}

			}

		}
		
		return $result;

	}

	// 取得商品選項

	public static function get_product_option( $data = array() )
	{

		$result = array();

		$shop_id = Session::get( 'Store' );

		$data = Product::get_product_list( $data, $shop_id );

		return $data;

	}

	// 取得商品資料

	public static function get_product_data( $data = array() )
	{

		$result = array();

		$shop_id = Session::get( 'Store' );

		$data = Product::get_product_list( $data, $shop_id );

		foreach ($data as &$row) 
		{

			$row->keep_for_days = $row->keep_for_days > 0 ? $row->keep_for_days : "無效期限制" ;

		}

		return $data;

	}

	// 將商品轉成陣列

	public static function get_product_list( $data )
	{

		$result = array();

		foreach ($data as $key1 => $row) 
		{
			foreach ($row as $key2 => $data) 
			{

				$result[$key1][$key2] = $data;

			}
		}

		return $result;

	}

	// 取得單一商品

	public static function get_single_product( $id )
	{

		$result = array();

		$shop_id = Session::get( 'Store' );

		$data = Product::get_single_product( $id, $shop_id );

		return $data;

	}

	// 取得單一商品規格

	public static function get_product_spec( $id )
	{

		$result = array();

		$data = Product::get_product_spec( $id );

		foreach ($data as $key => $row) 
		{

			$result[$key]["id"] = $row->id;

			$result[$key]["value"] = json_decode($row->spec_data, true);

		}

		return $result;

	}

	// 將商品轉成自動完成用的陣列

	public static function product_autocomplete( $data )
	{

		$result = array();

		foreach ($data as $key => $row) 
		{

			$result[$row->product_id] = $row->product_name;

		}

		return $result;

	}

	// 取得collection中的商品id

	public static function get_product_id_from_collection( $data )
	{

		$result = array();

		foreach ($data as $key => $row) 
		{

			$result = $row->product_id;

		}

		return $result;

	}

	public static function trans_product_spec_name( $data )
	{

		$_this = new self();

		$product_spec_column = $_this->get_product_spec_column(); 

		$mapping_array = array();

		foreach ($product_spec_column as $row) 
		{

			foreach ($row->option as &$row2) 
			{
				
				$mapping_array[$row->name][$row2->id] = $row2->name;

			}
	
		}		

		foreach ($data as &$row) 
		{

			foreach ($row['value'] as $key => &$row2) 
			{

				$row2 = $mapping_array[$key][$row2];
			
			}

		}

		return $data;

	}

	public static function is_spec_function_active()
	{

		$_this = new self();

		$product_spec_column = $_this->get_product_spec_column(); 
    
        $result = !empty($product_spec_column) ? 1 : 0 ;

		return $result;

	}

	public static function product_upload_sample_output( $column_header )
	{

		$_this = new self();

		$result = array();

		$column = array();

		$shop_id = Session::get( 'Store' );	

		$has_spec = $_this->is_spec_function_active(); 

		$data = Product::product_upload_sample_output( $shop_id );

		foreach ($column_header as $row) 
		{

			$column[] = $row['name'];

		}

		if (!empty($data)) 
		{

			foreach ($data as $key => $value) 
			{

				if ( in_array($key, $column) ) 
				{

					$result[$key] = $value;

				}

			}

		}

		return $result;

	}

	public static function product_spec_header_download_format( $spec_data )
	{

		$result = array();

		foreach ($spec_data as $row) 
		{

			$result[] = array( "name" => $row->name );

		}

		return $result;

	}

	public static function spec_upload_sample_output( $column_header )
	{

		$_this = new self();

		$result = array();

		$column = array();

		$shop_id = Session::get( 'Store' );	

		$has_spec = $_this->is_spec_function_active(); 

		$data = Product::spec_upload_sample_output( $shop_id );

		foreach ($data as &$row) 
		{

			$spec = json_decode( $row->spec_data );

			$spec = $_this->trans_product_spec_name(array( array( "value" => $spec ) ));

			foreach ($spec[0]['value'] as $key => $value) 
			{
				
				$row->$key = $value;

			}

		}

		foreach ($column_header as $row) 
		{

			$column[] = $row['name'];

		}

		foreach ($data as $index => $row) 
		{

			foreach ($row as $key => $value) 
			{

				if ( in_array($key, $column) ) 
				{

					$result[$index][$key] = $value;

				}

			}

		}

		return $result;

	}

}



