<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class Promo
{

	protected $table = "mall_shop_promo_date";

	public static function get_promo_price( $mall_shop_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->where( "mall_shop_id", "=", $mall_shop_id )
					->get();

		return $result;

	}

	public static function add_promo_data( $data )
	{

		$_this = new self();

		$result = DB::table($_this->table)->insert( $data );

		return $result;

	}

	public static function edit_promo_data( $data, $where )
	{

		$_this = new self();

		$result = DB::table($_this->table)->where('id', $where)->update($data);

		return $result;

	}

	public static function get_single_promo_data( $id )
	{

		$_this = new self();

		$result = DB::table($_this->table)->find( $id );

		return $result;

	}

	public static function is_promo_date_repeat( $data, $except_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)	
					->where("status", "=", 1)
					->where("mall_shop_id", "=", $data["mall_shop_id"])
					->where(function($query) use ($data){
						$query->whereBetween("start_date", array($data["start_date"], $data["end_date"]));
						$query->orWhere(function($query2) use ($data){
							$query2->whereBetween("end_date", array($data["start_date"], $data["end_date"]));
						});
					});

		$result = $except_id > 0 ? $result->where("id", "!=", $except_id) : $result ;

		$result = $result->get();

		return $result;

	}

	public static function get_active_promo_price( $mall_shop_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select("cost")
					->where("status", "=", 1)
					->where("mall_shop_id", "=", $mall_shop_id)
					->where("start_date", "<=", date("Y-m-d H:i:s"))
					->where("end_date", ">=", date("Y-m-d H:i:s"))
					->first();

		return $result;

	}

}


