<?php

namespace App\logic;

use App\model\Stock;
use Illuminate\Support\Facades\Session;
use App\logic\Product_logic;
use App\logic\ProductCategory_logic;

class Stock_logic extends Basetool
{

	protected $store_id = 0;


	public function __construct()
	{

		// 文字
		$this->store_id = Session::get( 'Store' );

	}


	// 新增格式

	public static function insert_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) ) 
		{

			$shop_id = $_this->store_id;

			foreach ($data as $row) 
			{

				if ( is_object($row) ) 
				{

					$result[] = array(
										"shop_id"      			=> $shop_id,
										"purchase_id"      		=> $row->id,
										"stock"      			=> $row->number,
										"created_at"    		=> date("Y-m-d H:i:s"),
										"updated_at"    		=> date("Y-m-d H:i:s")
									);

				}

			}	

		}

		return $result;

	}

	public static function add_stock( $data )
	{

		$result = !empty($data) && is_array($data) ? Stock::add_stock( $data ) : false;

		return $result;

	}

	public static function get_stock_batch_list( $data )
	{

		$_this = new self();

		$stock_list = array();

		$data["shop_id"] = $_this->store_id;

		$data = Stock::get_stock_batch_list( $data );

		foreach ($data as $row) 
		{

			if ( is_object($row) ) 
			{

				$stock_data = array(
									"stock" 				=> $row->stock,
									"product_name" 			=> $row->product_name,
									"in_warehouse_number" 	=> str_pad($row->in_warehouse_number, 8, "0", STR_PAD_LEFT),
									"in_warehouse_date" 	=> $row->in_warehouse_date,
									"deadline" 				=> $row->deadline
									// "spec_data" 			=> $spec_data,
								);

				$stock_list[] = $stock_data;

			}

		}

		$result = array(
						"data" 			=> $data,
						"stock_list" 	=> $stock_list
					);

		return $result;

	}

	public static function get_stock_total_list( $data )
	{

		$_this = new self();

		$stock_list = array();

		$data["shop_id"] = $_this->store_id;

		$data = Stock::get_stock_total_list( $data );

		foreach ($data as $row) 
		{

			if ( is_object($row) ) 
			{

				$stock_data = array(
									"stock" 				=> $row->stock,
									"product_name" 			=> $row->product_name,
								);

				$stock_list[] = $stock_data;

			}

		}

		$result = array(
						"data" 			=> $data,
						"stock_list" 	=> $stock_list
					);

		return $result;

	}

	public static function get_immediate_stock_list( $data )
	{

		$_this = new self();

		$stock_list = array();

		$data["shop_id"] = $_this->store_id;

		$data = Stock::get_immediate_stock_list( $data );

		foreach ($data as $row) 
		{

			if ( is_object($row) ) 
			{

				$stock_data = array(
									"stock" 				=> $row->stock,
									"product_name" 			=> $row->product_name,
									"in_warehouse_number" 	=> str_pad($row->in_warehouse_number, 8, "0", STR_PAD_LEFT),
									"in_warehouse_date" 	=> $row->in_warehouse_date,
									"deadline" 				=> $row->deadline,
								);

				$stock_list[] = $stock_data;

			}

		}

		$result = array(
						"data" 			=> $data,
						"stock_list" 	=> $stock_list
					);

		return $result;

	}

	public static function get_lack_of_stock_list( $data )
	{

		$_this = new self();

		$stock_list = array();

		$data["shop_id"] = $_this->store_id;

		$data = Stock::get_lack_of_stock_list( $data );

		foreach ($data as $row) 
		{

			if ( is_object($row) ) 
			{

				$stock_data = array(
									"stock" 				=> $row->stock,
									"product_name" 			=> $row->product_name,
									"safe_amount" 			=> $row->safe_amount,
								);

				$stock_list[] = $stock_data;

			}

		}

		$result = array(
						"data" 			=> $data,
						"stock_list" 	=> $stock_list
					);

		return $result;

	}

	public static function FIFO_get_stock_id( $product_id, $spec_id )
	{

		$result = array();

		if ( !empty($product_id) && is_array($product_id) ) 
		{

			$data = Stock::FIFO_get_stock_id( $product_id, $spec_id );

			if ( $data->count() > 0 ) 
			{
			
				$result = $data;

			}

		}

		return $result;

	}

	public static function cost_stock( $data )
	{

		$result = !empty($data) && is_array($data) ? Stock::cost_stock( $data ) : false;

		return $result;

	}

	public static function get_stock_analytics( $shop_id )
	{

		$result = array();

		if ( !empty($shop_id) && is_int($shop_id) ) 
		{

			$result = Stock::get_stock_analytics( $shop_id );

		}

		return $result;

	}

	public static function get_stock_analytics_add_parents_category( $data )
	{

		$result = array();

		$parents_category = array();

		$child_id = array();

		if ( !empty($data) ) 
		{

			foreach ($data as $row) 
			{

				if ( is_object($row) ) 
				{

					$child_id[] = $row->category;

				}
	 
			}

			$parents_category_data = ProductCategory_logic::get_mutli_parents_category_id( $child_id );

			$parents_id_name_trans = ProductCategory_logic::get_parents_id_name_trans();

			foreach ($data as &$row) 
			{

				if ( is_object($row) ) 
				{

					$row->parents_category = isset( $parents_category_data[$row->category] ) ? $parents_category_data[$row->category] : "無" ;
					
					$row->parents_category_name = isset( $parents_id_name_trans[$row->parents_category] ) ? $parents_id_name_trans[$row->parents_category] : "無" ;
	 
				}

			}

			$result = $data;

		}

		return $result;

	}

	public static function get_stock_and_safe_amount( $data )
	{

		$result = array();

		$product_id = array();

		$product_name = array();

		$safe_amount = array();

		$stock = array();

		if ( !empty($data) ) 
		{

			foreach ($data as $row) 
			{

				if ( is_object($row) ) 
				{			

					$product_id[] = $row->product_id;

				}

			}

		}

		if ( !empty($product_id) && is_array($product_id) ) 
		{

			$data = Stock::get_stock_and_safe_amount( $product_id );

			if ( !empty($data) ) 
			{

				foreach ($data as $row) 
				{

					if ( is_object($row) ) 
					{			

						$product_name[] = $row->product_name;

						$safe_amount[] = (int)$row->safe_amount;

						$stock[] = (int)$row->stock * 15;

					}

				}

				$result = array(
								"product_name"	=>	$product_name,
								"safe_amount"	=>	$safe_amount,
								"stock"			=>	$stock,
							);

			}

		}

		return $result;

	}

	// 組合列表資料

	public static function stock_total_list_data_bind( $OriData )
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$result = array(
                        "title" => array(
                        				$txt['product_name'],
                        				$txt['stock']
                        			),
                        "data" => array()
                    );

		if ( !empty($OriData) && is_array($OriData) ) 
		{

			foreach ($OriData as $row) 
			{

				if ( is_array($row) ) 
				{

					$data = array(
								"data" => array(
												"product_name" 		=> $row["product_name"],
												"stock" 			=> $row["stock"]
											)
							);
					
				}

				$result["data"][] = $data;
			
			}


		}

		return $result;

	}


	// 組合列表資料

	public static function stock_batch_list_data_bind( $OriData )
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$result = array(
                        "title" => array(
                        				$txt['product_name'],
                        				$txt['in_warehouse_number'],
                        				$txt['in_warehouse_date'],
                        				$txt['deadline'],
                        				$txt['stock']
                        			),
                        "data" => array()
                    );

		if ( !empty($OriData) && is_array($OriData) ) 
		{

			foreach ($OriData as $row) 
			{
	
				if ( is_array($row) ) 
				{

					$data = array(
								"data" => array(
												"product_name" 				=> $row["product_name"],
												"in_warehouse_number" 		=> $row["in_warehouse_number"],
												"in_warehouse_date" 		=> $row["in_warehouse_date"],
												"deadline" 					=> $row["deadline"],
												"stock" 					=> $row["stock"]
											)
							);
					
				}

				$result["data"][] = $data;
			
			}


		}

		return $result;

	}


	// 組合列表資料

	public static function stock_lack_list_data_bind( $OriData )
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$result = array(
                        "title" => array(
                        				$txt['product_name'],
                        				$txt['safe_amount'],
                        				$txt['stock']
                        			),
                        "data" => array()
                    );

		if ( !empty($OriData) && is_array($OriData) ) 
		{

			foreach ($OriData as $row) 
			{
	
				if ( is_array($row) ) 
				{

					$data = array(
								"data" => array(
												"product_name" 				=> $row["product_name"],
												"safe_amount" 				=> $row["safe_amount"],
												"stock" 					=> $row["stock"]
											)
							);
					
				}

				$result["data"][] = $data;
			
			}


		}

		return $result;

	}


	// 組合列表資料

	public static function stock_immediate_list_data_bind( $OriData )
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$result = array(
                        "title" => array(
                        				$txt['product_name'],
                        				$txt['in_warehouse_number'],
                        				$txt['in_warehouse_date'],
                        				$txt['deadline'],
                        				$txt['stock']
                        			),
                        "data" => array()
                    );

		if ( !empty($OriData) && is_array($OriData) ) 
		{

			foreach ($OriData as $row) 
			{
	
				if ( is_array($row) ) 
				{

					$data = array(
								"data" => array(
												"product_name" 				=> $row["product_name"],
												"in_warehouse_number" 		=> $row["in_warehouse_number"],
												"in_warehouse_date" 		=> $row["in_warehouse_date"],
												"deadline" 					=> $row["deadline"],
												"stock" 					=> $row["stock"]
											)
							);
					
				}

				$result["data"][] = $data;
			
			}


		}

		return $result;

	}

}
