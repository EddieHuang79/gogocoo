<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class Purchase
{

	protected $table = "purchase";

	protected $extra_table = "purchase_extra";

	protected $product = "product";

	protected $page_size = 15;

	public static function get_purchase_extra_column()
	{

		$_this = new self();

		$result = DB::select("SHOW COLUMNS FROM ". $_this->extra_table);

		return $result;

	}	

	public static function get_in_warehouse_number( $product_id, $product_spec_id, $purchase_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
						->select("in_warehouse_number")
						->where("product_id", "=", $product_id)
						->where("spec_id", "=", $product_spec_id)
						->where("id", "!=", $purchase_id)
						->orderBy("in_warehouse_number", "desc")
						->first();

		return $result;

	}

	public static function add_purchase( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)->insertGetId( $data );

		return $result;

	}

	public static function add_extra_purchase( $data )
	{

		$_this = new self();

		DB::table($_this->extra_table)->insert( $data );

	}

	public static function edit_purchase( $data, $purchase_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)->where("id", "=", $purchase_id)->update( $data );

	}

	public static function edit_purchase_extra_data( $data, $purchase_id )
	{

		$_this = new self();

		DB::table($_this->extra_table)->where('purchase_id', "=", $purchase_id)->update( $data );

	}

	public static function get_purchase_data( $data, $shop_id = 0 )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select(
								$_this->table.".*", 
								$_this->product.".product_name", 
								$_this->extra_table.".*"
							)
				    ->leftJoin($_this->product, $_this->product.'.id', '=', $_this->table.'.product_id')
				    ->leftJoin($_this->extra_table, $_this->table.'.id', '=', $_this->extra_table.'.purchase_id');

		$result = $shop_id > 0 ? $result->where("shop_id", "=", $shop_id) : $result ;

		$result = $result->orderBy("id", "desc")->paginate( $_this->page_size );

		return $result;

	}

	public static function get_single_purchase_data( $id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select(
								$_this->table.".*", 
								$_this->product.".product_name",
								$_this->extra_table.".*"
							)
				    ->leftJoin($_this->product, $_this->product.'.id', '=', $_this->table.'.product_id')
				    ->leftJoin($_this->extra_table, $_this->table.'.id', '=', $_this->extra_table.'.purchase_id')
				    ->where($_this->table.".id", "=", $id)
				    ->get();

		return $result;

	}

	public static function get_purchase_stock_data( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)->select("id", "number")->whereIn("id", $data)->get();

		return $result;

	}

	public static function purchase_verify( $data )
	{

		$_this = new self();

		DB::table($_this->table)->whereIn("id", $data)->update( array("status" => 2,"updated_at" => date("Y-m-d H:i:s")) );

	}

	public static function purchase_upload_sample_output( $shop_id = 0 )
	{

		$_this = new self();

		$result = DB::table($_this->table)
				->leftJoin($_this->extra_table, $_this->extra_table.'.purchase_id', '=', $_this->table.'.id')
				->leftJoin($_this->product, $_this->product.'.id', '=', $_this->table.'.product_id')
				->select($_this->product.'.product_name', $_this->table.'.*', $_this->extra_table.'.*');
				
		$result = $shop_id > 0 ? $result->where("shop_id", "=", $shop_id) : $result ;

		$result = !empty($data) ? $result->where($_this->extra_table.".purchase_id", "=", $data->purchase_id) : $result ;

		$result = $shop_id > 0 ? $result->where("shop_id", "=", $shop_id) : $result ;
		
		$result = $result->first();

		return $result;

	}

}
