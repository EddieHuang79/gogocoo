<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class Stock
{

	protected $table = "stock";

	protected $purchase_table = "purchase";

	protected $product_table = "product";

	protected $product_extra_table = "product_extra";
	
	protected $product_category_table = "product_category";

	protected $product_spec_table = "product_spec";

	protected $page_size = 15;

	public static function add_stock( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)->insert( $data );

		return $result;

	}

	public static function get_stock_batch_list( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->leftJoin($_this->purchase_table, $_this->table.'.purchase_id', '=', $_this->purchase_table.'.id')
					->leftJoin($_this->product_table, $_this->product_table.'.id', '=', $_this->purchase_table.'.product_id')
					->select(	
								$_this->table.".stock",
								$_this->product_table.".product_name",
								$_this->purchase_table.".in_warehouse_number",
								$_this->purchase_table.".in_warehouse_date",
								$_this->purchase_table.".deadline"
							)
					->where("stock", ">=", "0");
	
		$result = (int)$data["shop_id"] > 0 ? $result->where($_this->purchase_table.".shop_id", "=", $data["shop_id"]) : $result ;

		$result = $result->paginate( $_this->page_size );	

		return $result;

	}

	public static function get_stock_total_list( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->leftJoin($_this->purchase_table, $_this->table.'.purchase_id', '=', $_this->purchase_table.'.id')
					->leftJoin($_this->product_table, $_this->product_table.'.id', '=', $_this->purchase_table.'.product_id')
					->select(	
								\DB::raw('SUM(stock) as stock'),
								$_this->product_table.".product_name"
							)
					->where("stock", ">=", "0");

		$result = (int)$data["shop_id"] > 0 ? $result->where($_this->purchase_table.".shop_id", "=", $data["shop_id"]) : $result ;

		$result = $result
					->groupBy($_this->purchase_table.".product_id")
					->orderBy("stock", "desc")
					->orderBy($_this->table.".id", "desc")
					->get();

		return $result;

	}

	public static function get_immediate_stock_list( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->leftJoin($_this->purchase_table, $_this->table.'.purchase_id', '=', $_this->purchase_table.'.id')
					->leftJoin($_this->product_table, $_this->product_table.'.id', '=', $_this->purchase_table.'.product_id')
					->select(	
								$_this->table.".stock",
								$_this->product_table.".product_name",
								$_this->purchase_table.".in_warehouse_number",
								$_this->purchase_table.".in_warehouse_date",
								$_this->purchase_table.".deadline"
							)
					->where("deadline", "<=", DB::raw('curdate() + INTERVAL 1 MONTH'))
					->where("stock", ">", "0");

		$result = (int)$data["shop_id"] > 0 ? $result->where($_this->purchase_table.".shop_id", "=", $data["shop_id"]) : $result ;

		$result = $result->orderBy("deadline", "ASC")->paginate( $_this->page_size );	

		return $result;

	}

	public static function get_lack_of_stock_list( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->leftJoin($_this->purchase_table, $_this->table.'.purchase_id', '=', $_this->purchase_table.'.id')
					->leftJoin($_this->product_table, $_this->product_table.'.id', '=', $_this->purchase_table.'.product_id')
					->select(	
								\DB::raw('SUM(stock) as stock'),
								$_this->product_table.".product_name",
								$_this->product_table.".safe_amount"
							)
					->where("stock", ">=", "0");

		$result = (int)$data["shop_id"] > 0 ? $result->where($_this->purchase_table.".shop_id", "=", $data["shop_id"]) : $result ;

		$result = $result
					->groupBy($_this->purchase_table.".product_id")
					->havingRaw('SUM(stock) <= safe_amount')
					->orderBy("stock", "desc")
					->orderBy($_this->table.".id", "desc")
					->get();		

		return $result;

	}

	public static function FIFO_get_stock_id( $product_id, $spec_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->leftJoin($_this->purchase_table, $_this->table.'.purchase_id', '=', $_this->purchase_table.'.id')
					->select(
								$_this->table.".id", 
								"in_warehouse_number", 
								"product_id", 
								"spec_id", 
								"stock"
							)
					->where("stock", ">", "0")
					->whereIn("product_id", $product_id);

		$result = !empty($spec_id) ? $result->whereIn("spec_id", $spec_id) : $result ;
		
		$result = $result->orderBy("in_warehouse_number")->get();

		return $result;

	}

	public static function cost_stock( $data )
	{

		$_this = new self();

		$stock_id = array_keys($data);

		foreach ($data as $row) 
		{

			$stock_data = DB::table($_this->table)->select("stock")->find( $row["stock_id"] );

			$update_stock = $stock_data->stock - $row["cost_number"];

			DB::table($_this->table)->where("id", "=", $row["stock_id"])->update( array( "stock" => $update_stock ) );

		}

	}

	public static function get_stock_analytics( $shop_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->leftJoin($_this->purchase_table, $_this->table.'.purchase_id', '=', $_this->purchase_table.'.id')
					->leftJoin($_this->product_extra_table, $_this->product_extra_table.'.product_id', '=', $_this->purchase_table.'.product_id')
					->leftJoin($_this->product_category_table, $_this->product_category_table.'.id', '=', $_this->product_extra_table.'.category')
					->select(	
								$_this->product_extra_table.".category",
								$_this->product_category_table.".name",
								\DB::raw('SUM(stock) as stock')
							)
					->where("stock", ">=", "0")
					->where($_this->purchase_table.".shop_id", "=", $shop_id)
					->groupBy($_this->product_extra_table.".category")
					->get();	

		return $result;

	}

	public static function get_stock_and_safe_amount( $product_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->leftJoin($_this->purchase_table, $_this->table.'.purchase_id', '=', $_this->purchase_table.'.id')
					->leftJoin($_this->product_table, $_this->product_table.'.id', '=', $_this->purchase_table.'.product_id')
					->select(	
								$_this->product_table.".product_name",
								$_this->product_table.".safe_amount",
								\DB::raw('SUM(stock) as stock')
							)
					->where("stock", ">=", "0")
					->whereIn($_this->product_table.".id", $product_id)
					->groupBy($_this->purchase_table.".product_id")
					->get();	

		return $result;

	}

}
