<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class Product
{

	protected $table = "product";

	protected $extra_table = "product_extra";

	protected $spec_table = "product_spec";

	protected $option_table = "option";

	protected $page_size = 15;

	public static function get_product_extra_column()
	{

		$_this = new self();

		$result = DB::select("SHOW COLUMNS FROM ". $_this->extra_table);

		return $result;

	}	

	public static function get_product_spec_column()
	{

		$_this = new self();

		$result = DB::table($_this->option_table)->select("value")->where("status", "=", "1")->where("type", "=", "2")->get();

		return $result;

	}

	public static function add_product( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)->insertGetId( $data );

		return $result;

	}

	public static function add_extra_data( $data )
	{

		$_this = new self();

		DB::table($_this->extra_table)->insert( $data );

	}

	public static function add_product_spec_data( $data )
	{

		$_this = new self();

		DB::table($_this->spec_table)->insert( $data );

	}

	public static function edit_product( $data, $product_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)->where('id', "=", $product_id)->update( $data );

		return $result;

	}

	public static function edit_product_extra_data( $data, $product_id )
	{

		$_this = new self();

		DB::table($_this->extra_table)->where('product_id', "=", $product_id)->update( $data );

	}

	public static function edit_product_spec_data( $data )
	{

		$_this = new self();

		foreach ($data as $spec_id => $row) 
		{

			DB::table($_this->spec_table)->where('id', "=", $spec_id)->update( $row );
		
		}

	}

	public static function get_product_list( $data, $shop_id = 0  )
	{

		$_this = new self();

		$page_size = $_this->page_size;

		$result = DB::table($_this->table)
				->leftJoin($_this->extra_table, $_this->extra_table.'.product_id', '=', $_this->table.'.id')
				->select(
							$_this->table.'.*',
							$_this->extra_table.'.*'
						);
		
		$result = $shop_id > 0 ? $result->where("shop_id", "=", $shop_id) : $result ;

		if ( !empty($data) ) 
		{

			foreach ($data as $key => $row) 
			{

				$column = $row[0];
				$method = $row[1];
				$data = $row[2];

				$result = $result->where( $column, $method, $data );
			
			}

		}
		
		$result = $result->paginate( $page_size );

		return $result;

	}

	public static function get_single_product( $id, $shop_id = 0 )
	{

		$_this = new self();

		$page_size = $_this->page_size;

		$result = DB::table($_this->table)
				->leftJoin($_this->extra_table, $_this->extra_table.'.product_id', '=', $_this->table.'.id')
				->select($_this->extra_table.'.*', $_this->table.'.product_name', $_this->table.'.safe_amount', $_this->table.'.created_at', $_this->table.'.updated_at')
				->where($_this->table.".id", "=", $id);
		
		$result = $shop_id > 0 ? $result->where("shop_id", "=", $shop_id) : $result ;
		
		$result = $result->first();

		return $result;

	}

	public static function get_product_spec( $id )
	{

		$_this = new self();

		$page_size = $_this->page_size;

		$result = DB::table($_this->spec_table)
				->select($_this->spec_table.'.id',$_this->spec_table.'.spec_data')
				->where("product_id", "=", $id)
				->get();
				
		return $result;

	}

	public static function product_upload_sample_output( $shop_id = 0 )
	{

		$_this = new self();

		$result = DB::table($_this->table)
				->leftJoin($_this->extra_table, $_this->extra_table.'.product_id', '=', $_this->table.'.id')
				->select($_this->table.'.*', $_this->extra_table.'.*');
				
		$result = $shop_id > 0 ? $result->where("shop_id", "=", $shop_id) : $result ;
		
		$result = $result->first();

		return $result;

	}

	public static function spec_upload_sample_output( $shop_id = 0 )
	{

		$_this = new self();

		$data = DB::table($_this->extra_table)->select("product_id");

		$data = $shop_id > 0 ? $data->where("shop_id", "=", $shop_id) : $data ;

		$data = $data->first();

		$result = DB::table($_this->table)
				->leftJoin($_this->extra_table, $_this->extra_table.'.product_id', '=', $_this->table.'.id')
				->leftJoin($_this->spec_table, $_this->spec_table.'.product_id', '=', $_this->table.'.id')
				->select($_this->table.'.*', $_this->extra_table.'.*', $_this->spec_table.'.spec_data');
				
		$result = $shop_id > 0 ? $result->where("shop_id", "=", $shop_id) : $result ;

		$result = !empty($data) ? $result->where($_this->extra_table.".product_id", "=", $data->product_id) : $result ;
		
		$result = $result->get();

		return $result;

	}

	public static function get_product_id_by_product_name( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)
				->whereIn("product_name", $data)
				->get();
				
		return $result;

	}

}
