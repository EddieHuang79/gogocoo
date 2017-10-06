<?php

namespace App\logic;

use App\model\Purchase;
use Illuminate\Support\Facades\Session;
use App\logic\Product_logic;
use App\logic\Option_logic;

class Purchase_logic extends Basetool
{

	protected $status_txt = array(
								1	=>	"未入帳",
								2	=>	"已入帳"
							);

	protected $in_warehouse_category;

	protected $warehouse_txt = array(
									1 => "自家倉庫"
								);

	public function __construct()
	{

		// 文字
		$this->in_warehouse_category = Option_logic::get_in_warehouse_category();

	}

	// 新增格式

	public static function insert_format( $data )
	{

		$_this = new self();

		$result = array();

		$shop_id = Session::get( 'Store' );

		if ( !empty($data) && is_array($data) ) 
		{

			$spec_id = isset($data["spec_id"]) ? intval($data["spec_id"]) : 0 ;

			// 商品data

			$product_name = isset($data["product_name"]) ? $_this->strFilter($data["product_name"]) : "" ;

			if ( !empty($product_name) ) 
			{

		        $option = array( array("product_name", "=", $product_name) );

		        $product_data = Product_logic::get_product_data( $option );

		        foreach ($product_data as $row) 
		        {

		        	$product_id = $row->product_id;

		        	$deadline = (int)$row->keep_for_days > 0 ? date("Y-m-d", mktime( 0, 0, 0, date("m", strtotime($data["in_warehouse_date"])), date("d", strtotime($data["in_warehouse_date"]))+$row->keep_for_days, date("Y", strtotime($data["in_warehouse_date"])) ) ) : "2050-12-31" ;

		        }

				// 批號

				$in_warehouse_number = $_this->get_in_warehouse_number( $product_id, $spec_id, 0 );

				$result = array(
				            "shop_id"      			=> !empty($shop_id) ? $shop_id : 1,
				            "product_id"      		=> $product_id,
				            "spec_id"      			=> $spec_id,
				            "in_warehouse_number"	=> $in_warehouse_number,
				            "number"       			=> isset($data["number"]) ? intval($data["number"]) : 0,
				            "in_warehouse_date" 	=> isset($data["in_warehouse_date"]) ? date("Y-m-d", strtotime($data["in_warehouse_date"])) : date("Y-m-d"),
				            "deadline" 				=> $deadline,
				            "category"    			=> isset($data["category"]) ? intval($data["category"]) : 1,
				            "status"    			=> 1,
				            "created_at"    		=> date("Y-m-d H:i:s"),
				            "updated_at"    		=> date("Y-m-d H:i:s")
				         );

			}

		}

		return $result;

	}


	// 新增附屬欄位格式

	public static function insert_extra_format( $data, $purchase_extra_column, $purchase_id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) && !empty($purchase_extra_column) && is_array($purchase_extra_column) && !empty($purchase_id) && is_int($purchase_id) ) 
		{

			$result["purchase_id"] = $purchase_id;

			foreach ($purchase_extra_column as $row_data) 
			{

				$default_value = $row_data['type'] == 'number' ? 0 : "" ;

				$result[$row_data['name']] = isset($data[$row_data['name']]) && !empty($data[$row_data['name']]) ? trim($data[$row_data['name']]) : $default_value ;

			}

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

			$purchase_id = intval($data["purchase_id"]);

			$spec_id = isset($data["spec_id"]) ? intval($data["spec_id"]) : 0 ;

			// 商品data

			$product_name = isset($data["product_name"]) ? $_this->strFilter($data["product_name"]) : "" ;

	        $option = array( array("product_name", "=", $product_name) );

	        $product_data = Product_logic::get_product_data( $option );

	        foreach ($product_data as $row) 
	        {

	        	$product_id = $row->product_id;
	        
	        	$deadline = (int)$row->keep_for_days > 0 ? date("Y-m-d", mktime( 0, 0, 0, date("m", strtotime($data["in_warehouse_date"])), date("d", strtotime($data["in_warehouse_date"]))+$row->keep_for_days, date("Y", strtotime($data["in_warehouse_date"])) ) ) : "2050-12-31" ;        	

	        }

			// 批號

			$in_warehouse_number = $_this->get_in_warehouse_number( (int)$product_id, (int)$spec_id, (int)$purchase_id );

			$result = array(
			            "product_id"      		=> $product_id,
			            "spec_id"      			=> $spec_id,
			            "in_warehouse_number"	=> $in_warehouse_number,
			            "number"       			=> isset($data["number"]) ? intval($data["number"]) : 0,
			            "in_warehouse_date" 	=> isset($data["in_warehouse_date"]) ? date("Y-m-d", strtotime($data["in_warehouse_date"])) : date("Y-m-d"),
			            "deadline" 				=> $deadline,		           
			            "category"    			=> isset($data["category"]) ? intval($data["category"]) : 1,
			            "updated_at"    		=> date("Y-m-d H:i:s")
			         );

		}

		return $result;

	}


	// 附屬資料更新格式

	public static function update_extra_format( $data, $purchase_extra_column )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) && !empty($purchase_extra_column) && is_array($purchase_extra_column) ) 
		{

			foreach ($purchase_extra_column as $row_data) 
			{

				$default_value = $row_data['type'] == 'number' ? 0 : "" ;

				$result[$row_data['name']] = isset($data[$row_data['name']]) && !empty($data[$row_data['name']]) ? trim($data[$row_data['name']]) : $default_value ;

			}

		}

		return $result;

	}


	// 取得入庫單號

	public static function get_in_warehouse_number( $product_id, $product_spec_id = 0, $purchase_id = 0 )
	{

		$result = false;

		if ( !empty($product_id) && is_int($product_id) ) 
		{

			$data = Purchase::get_in_warehouse_number( $product_id, $product_spec_id, $purchase_id);

			$result = !empty($data) ? $data->in_warehouse_number + 1 : 1 ;

		}

		return $result;

	}


	// 新增採購

	public static function add_purchase( $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($data['product_id']) ) 
		{

			$result = Purchase::add_purchase( $data );

		}

		return $result;

	}


	// 新增採購附屬資料

	public static function add_extra_purchase( $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) ) 
		{

			Purchase::add_extra_purchase( $data );

			$result = true;

		}

		return $result;

	}


	// 修改採購資料

	public static function edit_purchase( $data, $purchase_id )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($purchase_id) && is_int($purchase_id) ) 
		{

			Purchase::edit_purchase( $data, $purchase_id );

			$result = true;

		}

		return $result;

	}


	// 修改採購附屬資料

	public static function edit_purchase_extra_data( $data, $purchase_id )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($purchase_id) && is_int($purchase_id) ) 
		{

			Purchase::edit_purchase_extra_data( $data, $purchase_id );

			$result = true;

		}

		return $result;

	}


	// 取得採購資料

	public static function get_purchase_data( $data = array() )
	{

		$shop_id = Session::get( 'Store' );

		return Purchase::get_purchase_data( $data, $shop_id );

	}


	// 取得採購列表

	public static function get_purchase_list( $data, $option_data )
	{

		$_this = new self();

		$status_txt = $_this->status_txt;

		$in_warehouse_category_txt = $_this->in_warehouse_category;

		$result = array();

		if ( !empty($data) ) 
		{

			foreach ($data as $key1 => $row) 
			{

				if ( is_object($row) ) 
				{

					foreach ($row as $key2 => $data) 
					{

						$result[$key1][$key2] = $data;

					}

					$category = $row->category;

					$result[$key1]['in_warehouse_number_txt'] = str_pad($row->in_warehouse_number, 8, "0", STR_PAD_LEFT);

					$result[$key1]['in_warehouse_category_txt'] = $in_warehouse_category_txt->$category;

					$result[$key1]['status_txt'] = $status_txt[$row->status];

					foreach ($option_data as $key2 => $data) 
					{

						$result[$key1][$key2.'_txt'] = isset( $data[$row->$key2] ) ? $data[$row->$key2] : "" ;
					
					}

				}

			}

		}

		return $result;

	}


	// 取得單筆資料

	public static function get_single_purchase_data( $id )
	{

		$result = array();

		if ( !empty($id) && is_int($id) ) 
		{

			$result = Purchase::get_single_purchase_data( $id );

		}

		return $result;

	}


	// 取得本次進貨入庫數

	public static function get_purchase_stock_data( $data )
	{

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = Purchase::get_purchase_stock_data( $data );

		}

		return $result;

	}


	// 進貨入帳

	public static function purchase_verify( $data )
	{

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			Purchase::purchase_verify( $data );

		}

		return $result;

	}


	// 取得額外採購欄位

	public static function get_purchase_extra_column()
	{

		$result = array();

		$data = Purchase::get_purchase_extra_column();

		unset($data[0], $data[1]);

		if ( !empty($data) ) 
		{

			foreach ($data as $key => $row) 
			{

				$result[$key]["name"] = $row->Field;

				$result[$key]["show_on_page"] = true;

				// 基本案例

				if ( strpos($row->Type, "varchar") !== false || strpos($row->Type, "date") !== false ) 
				{
					$result[$key]["type"] = "text";
				}

				if ( strpos($row->Type, "int") !== false ) 
				{
					$result[$key]["type"] = "number";
				}

				// 特例

				if ( $row->Field == 'warehouse_id' ) 
				{
					$result[$key]["type"] = "select";
				}

				if ( $row->Field == 'deadline' ) 
				{
					$result[$key]["show_on_page"] = false;
				}

			}

		}


		return $result;

	}


	// 採購範例

	public static function purchase_upload_sample_output( $column_header )
	{

		$_this = new self();

		$result = array();

		$column = array();

		$shop_id = Session::get( 'Store' );	

		if ( !empty($column_header) && is_array($column_header) ) 
		{

			foreach ($column_header as $row) 
			{

				$column[] = $row['name'];

			}

		}

		$data = Purchase::purchase_upload_sample_output( $shop_id );

		if ( !empty($data) ) 
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


	// 取得入庫總和

	public static function get_purchase_sum( $week_date, $shop_id, $status )
	{

		$result = new \stdClass();

		if ( !empty($week_date) && !empty($shop_id) && !empty($status) ) 
		{

			$result = Purchase::get_purchase_sum( $week_date, $shop_id, $status );

		}

		return $result;

	}

}
