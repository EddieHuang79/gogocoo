<?php

namespace App\logic;

use App\model\Stock;
use Illuminate\Support\Facades\Session;
use App\logic\Product_logic;
use App\logic\ProductCategory_logic;

class Stock_logic extends Basetool
{

	// 新增格式

	public static function insert_format( $data )
	{

		$_this = new self();

		$shop_id = Session::get( 'Store' );

		foreach ($data as $row) 
		{
			$result[] = array(
								"shop_id"      			=> $shop_id,
								"purchase_id"      		=> $row->id,
								"stock"      			=> $row->number,
								"created_at"    		=> date("Y-m-d H:i:s"),
								"updated_at"    		=> date("Y-m-d H:i:s")
							);
		}


		return $result;

	}

	public static function add_stock( $data )
	{

		Stock::add_stock( $data );

	}

	public static function get_stock_batch_list( $data, $has_spec )
	{

		$stock_list = array();

		$data = Stock::get_stock_batch_list( $data, $has_spec );

		foreach ($data as $row) 
		{

			$stock_data = array(
								"stock" 				=> $row->stock,
								"product_name" 			=> $row->product_name,
								"in_warehouse_number" 	=> str_pad($row->in_warehouse_number, 8, "0", STR_PAD_LEFT),
								"in_warehouse_date" 	=> $row->in_warehouse_date,
								"deadline" 				=> $row->deadline
								// "spec_data" 			=> $spec_data,
							);

			if ( $has_spec ) 
			{

				$spec = json_decode($row->spec_data, true);

				$product_spec = Product_logic::trans_product_spec_name( array( array( "value" => $spec ) ) );

				$product_spec = array_shift($product_spec);

				$spec_data = "";

				foreach ($product_spec['value'] as $key => $value) 
				{

					switch ($key) 
					{

						case 'color':

							$spec_data .= "顏色: " ;

							break;

						case 'size':

							$spec_data .= "尺寸: " ;

							break;

						case 'font_type':

							$spec_data .= "字型: " ;

							break;
				
					}

					$spec_data .= $value . "; "; 
				
				}

				$stock_data['spec_data'] = $spec_data ;

			}

			$stock_list[] = $stock_data;

		}

		$result = array(
						"data" 			=> $data,
						"stock_list" 	=> $stock_list
					);

		return $result;

	}

	public static function get_stock_total_list( $data, $has_spec )
	{

		$stock_list = array();

		$data = Stock::get_stock_total_list( $data, $has_spec );

		foreach ($data as $row) 
		{

			$stock_data = array(
								"stock" 				=> $row->stock,
								"product_name" 			=> $row->product_name,
							);

			if ( $has_spec ) 
			{

				$spec = json_decode($row->spec_data, true);

				$product_spec = Product_logic::trans_product_spec_name( array( array( "value" => $spec ) ) );

				$product_spec = array_shift($product_spec);

				$spec_data = "";

				foreach ($product_spec['value'] as $key => $value) 
				{

					switch ($key) 
					{

						case 'color':

							$spec_data .= "顏色: " ;

							break;

						case 'size':

							$spec_data .= "尺寸: " ;

							break;

						case 'font_type':

							$spec_data .= "字型: " ;

							break;
				
					}

					$spec_data .= $value . "; "; 
				
				}

				$stock_data['spec_data'] = $spec_data ;

			}

			$stock_list[] = $stock_data;

		}

		$result = array(
						"data" 			=> $data,
						"stock_list" 	=> $stock_list
					);

		return $result;

	}

	public static function get_immediate_stock_list( $data, $has_spec )
	{

		$stock_list = array();

		$data = Stock::get_immediate_stock_list( $data, $has_spec );

		foreach ($data as $row) 
		{

			$stock_data = array(
								"stock" 				=> $row->stock,
								"product_name" 			=> $row->product_name,
								"in_warehouse_number" 	=> str_pad($row->in_warehouse_number, 8, "0", STR_PAD_LEFT),
								"in_warehouse_date" 	=> $row->in_warehouse_date,
								"deadline" 				=> $row->deadline,
							);

			if ( $has_spec ) 
			{

				$spec = json_decode($row->spec_data, true);

				$product_spec = Product_logic::trans_product_spec_name( array( array( "value" => $spec ) ) );

				$product_spec = array_shift($product_spec);

				$spec_data = "";

				foreach ($product_spec['value'] as $key => $value) 
				{

					switch ($key) 
					{

						case 'color':

							$spec_data .= "顏色: " ;

							break;

						case 'size':

							$spec_data .= "尺寸: " ;

							break;

						case 'font_type':

							$spec_data .= "字型: " ;

							break;
				
					}

					$spec_data .= $value . "; "; 
				
				}

				$stock_data['spec_data'] = $spec_data ;

			}

			$stock_list[] = $stock_data;

		}

		$result = array(
						"data" 			=> $data,
						"stock_list" 	=> $stock_list
					);

		return $result;

	}

	public static function get_lack_of_stock_list( $data, $has_spec )
	{

		$stock_list = array();

		$data = Stock::get_lack_of_stock_list( $data, $has_spec );

		foreach ($data as $row) 
		{

			$stock_data = array(
								"stock" 				=> $row->stock,
								"product_name" 			=> $row->product_name,
								"safe_amount" 			=> $row->safe_amount,
							);

			if ( $has_spec ) 
			{

				$spec = json_decode($row->spec_data, true);

				$product_spec = Product_logic::trans_product_spec_name( array( array( "value" => $spec ) ) );

				$product_spec = array_shift($product_spec);

				$spec_data = "";

				foreach ($product_spec['value'] as $key => $value) 
				{

					switch ($key) 
					{

						case 'color':

							$spec_data .= "顏色: " ;

							break;

						case 'size':

							$spec_data .= "尺寸: " ;

							break;

						case 'font_type':

							$spec_data .= "字型: " ;

							break;
				
					}

					$spec_data .= $value . "; "; 
				
				}

				$stock_data['spec_data'] = $spec_data ;

			}

			$stock_list[] = $stock_data;

		}

		$result = array(
						"data" 			=> $data,
						"stock_list" 	=> $stock_list
					);

		return $result;

	}

	public static function FIFO_get_stock_id( $product_id, $spec_id )
	{

		return Stock::FIFO_get_stock_id( $product_id, $spec_id );

	}

	public static function cost_stock( $data )
	{

		return Stock::cost_stock( $data );

	}

	public static function get_stock_analytics( $shop_id )
	{

		return Stock::get_stock_analytics( $shop_id );

	}

	public static function get_stock_analytics_add_parents_category( $data )
	{

		$result = array();

		$parents_category = array();

		$child_id = array();

		foreach ($data as $row) 
		{

			$child_id[] = $row->category;
 
		}

		$parents_category_data = ProductCategory_logic::get_mutli_parents_category_id( $child_id );

		$parents_id_name_trans = ProductCategory_logic::get_parents_id_name_trans();

		foreach ($data as &$row) 
		{

			$row->parents_category = isset( $parents_category_data[$row->category] ) ? $parents_category_data[$row->category] : "無" ;
			
			$row->parents_category_name = isset( $parents_id_name_trans[$row->parents_category] ) ? $parents_id_name_trans[$row->parents_category] : "無" ;
 
		}

		$result = $data;

		return $result;

	}

	public static function get_stock_and_safe_amount( $data )
	{

		$result = array();

		$product_id = array();

		$product_name = array();

		$safe_amount = array();

		$stock = array();

		foreach ($data as $row) 
		{

			$product_id[] = $row->product_id;

		}

		$data = Stock::get_stock_and_safe_amount( $product_id );

		foreach ($data as $row) 
		{

			$product_name[] = $row->product_name;

			$safe_amount[] = (int)$row->safe_amount;

			$stock[] = (int)$row->stock * 15;

		}

		$result = array(
						"product_name"	=>	$product_name,
						"safe_amount"	=>	$safe_amount,
						"stock"			=>	$stock,
					);

		return $result;

	}

}
