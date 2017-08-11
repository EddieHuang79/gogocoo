<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Shop extends Model
{

	protected $table = "mall_product";
	protected $spec_table = "mall_product_spec";
	protected $record_table = "mall_record";

	public static function shop_product_list()
	{

		$_this = new self();

		$data = DB::table($_this->table)
					->where("public", "=", 1)
					->Where(function ($query) {
						$query->orWhere(function($sub_query){

							$sub_query->where('start_date', '!=', '1970-01-01 00:00:00')
										->where('end_date', '!=', '1970-01-01 23:59:59')
										->where('start_date', '<=', date("Y-m-d H:i:s"))
										->where('end_date', '>=', date("Y-m-d H:i:s"));

						});
						$query->orWhere(function($sub_query){

							$sub_query->where('start_date', '=', '1970-01-01 00:00:00')
										->where('end_date', '!=', '1970-01-01 23:59:59')
										->where('end_date', '>=', date("Y-m-d H:i:s"));		

						});
						$query->orWhere(function($sub_query){

							$sub_query->where('start_date', '!=', '1970-01-01 00:00:00')
										->where('end_date', '=', '1970-01-01 23:59:59')
										->where('start_date', '<=', date("Y-m-d H:i:s"));

						});
						$query->orWhere(function($sub_query){

							$sub_query->where('start_date', '=', '1970-01-01 00:00:00')
										->where('end_date', '=', '1970-01-01 23:59:59');

						});
					})
					->paginate(15);

		return $data;

	}

	public static function service_buy( $data )
	{

		$_this = new self();

		DB::table($_this->service_user)->insertGetId($data);

	}

	public static function shop_record( $data )
	{

		$_this = new self();

		$data = DB::table($_this->record_table)
					->select($_this->spec_table.".*",$_this->table.'.product_name',$_this->record_table.'.deadline',$_this->record_table.'.paid_at',$_this->record_table.'.number')
					->leftJoin($_this->table, $_this->record_table.'.mall_product_id', '=', $_this->table.'.id')
					->leftJoin($_this->spec_table, $_this->record_table.'.mall_product_spec_id', '=', $_this->spec_table.'.id')
					->where($_this->record_table.".user_id", "=", $data["user_id"])
					->paginate(15);

		return $data;

	}

	public static function get_mall_product( $mall_product_id )
	{

		$_this = new self();

		$data = DB::table($_this->table)
					->select( $_this->table.".id", $_this->table.".product_name", $_this->table.".description", $_this->table.".pic", $_this->spec_table.".cost", $_this->spec_table.".date_spec", $_this->spec_table.".id as spec_id" )
					->leftJoin($_this->spec_table, $_this->table.'.id', '=', $_this->spec_table.'.mall_product_id')
					->where($_this->table.".id", "=", $mall_product_id)
					->get();

		return $data;

	}

	public static function get_product_spec( $spec_id )
	{

		$_this = new self();

		$data = DB::table($_this->spec_table)->find( $spec_id );

		return $data;

	}

	public static function shop_buy_insert( $data )
	{

		$_this = new self();

		$data = DB::table($_this->record_table)->insertGetId( $data );

		return $data > 0 ? true : false;

	}

}
