<?php

namespace App\logic;

use App\model\Stock;
use Illuminate\Support\Facades\Session;
use App\logic\Product_logic;

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

}
