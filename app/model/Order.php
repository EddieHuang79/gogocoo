<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class Order
{

	protected $table = "order";

	protected $extra_table = "order_extra";

	protected $order_stock_record_table = "order_stock_record";

	protected $product = "product";

	protected $page_size = 15;

	public static function get_order_extra_column()
	{

		$_this = new self();

		$result = DB::select("SHOW COLUMNS FROM ". $_this->extra_table);

		return $result;

	}	

	public static function get_order_number( $order_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
						->select("order_number")
						->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])
						->where("id", "!=", $order_id)
						->orderBy("order_number", "desc")
						->first();

		return $result;

	}

	public static function add_order( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)->insertGetId( $data );

		return $result;

	}

	public static function add_extra_order( $data )
	{

		$_this = new self();

		DB::table($_this->extra_table)->insert( $data );

	}

	public static function edit_order( $data, $order_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)->where("id", "=", $order_id)->update( $data );

	}

	public static function edit_order_extra_data( $data, $order_id )
	{

		$_this = new self();

		DB::table($_this->extra_table)->where('order_id', "=", $order_id)->update( $data );

	}

	public static function get_order_data( $data, $shop_id = 0 )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select(
								$_this->table.".*", 
								$_this->product.".product_name", 
								$_this->extra_table.".*"
							)
				    ->leftJoin($_this->product, $_this->product.'.id', '=', $_this->table.'.product_id')
				    ->leftJoin($_this->extra_table, $_this->table.'.id', '=', $_this->extra_table.'.order_id');

		$result = $shop_id > 0 ? $result->where("shop_id", "=", $shop_id) : $result ;

		$result = $result->orderBy("id", "desc")->paginate( $_this->page_size );

		return $result;

	}

	public static function get_single_order_data( $id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select(
								$_this->table.".*", 
								$_this->product.".product_name",
								$_this->extra_table.".*"
							)
				    ->leftJoin($_this->product, $_this->product.'.id', '=', $_this->table.'.product_id')
				    ->leftJoin($_this->extra_table, $_this->table.'.id', '=', $_this->extra_table.'.order_id')
				    ->where($_this->table.".id", "=", $id)
				    ->get();

		return $result;

	}

	public static function get_order_data_for_verify( $order_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select(
								"id",
								"product_id",
								"spec_id",
								"order_number",
								"number"
							)
					->where("status", "=", "1")
					->whereIn("id", $order_id)
					->get();

		return $result;

	}

	public static function change_status( $order_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)->where("id", $order_id)->update( array("status" => 2) );

	}

	public static function add_order_stock_record( $data )
	{

		$_this = new self();

		DB::table($_this->order_stock_record_table)->insert( $data );

	}

	public static function order_upload_sample_output( $shop_id = 0 )
	{

		$_this = new self();

		$result = DB::table($_this->table)
				->leftJoin($_this->extra_table, $_this->extra_table.'.order_id', '=', $_this->table.'.id')
				->leftJoin($_this->product, $_this->product.'.id', '=', $_this->table.'.product_id')
				->select($_this->product.'.product_name',$_this->table.'.*', $_this->extra_table.'.*');
				
		$result = $shop_id > 0 ? $result->where("shop_id", "=", $shop_id) : $result ;
		
		$result = $result->first();

		return $result;

	}

}
