<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class ProductCategory
{

	protected $table = "product_category";

	protected $page_size = 15;

	public static function get_parents_category_list( $shop_id, $excpet_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select(
								"id",
								"name"
							)
					->whereIn("shop_id", array( 0, $shop_id))
					->where("parents_id", "=", "0");
		$result = $excpet_id > 0 ? $result->where("id", "!=", $excpet_id) : $result ;
		$result = $result->get();

		return $result;

	}

	public static function get_product_category_data( $shop_id )
	{

		$_this = new self();

		$page_size = $_this->page_size;

		$result = DB::table($_this->table)
					->select("*")
					->whereIn("shop_id", array( 0, $shop_id))
					->paginate( $page_size );

		return $result;

	}

	public static function get_single_product_category( $id )
	{

		$_this = new self();

		$page_size = $_this->page_size;

		$result = DB::table($_this->table)
					->select("*")
					->find( $id );

		return $result;

	}

	public static function add_product_category( $data )
	{

		$_this = new self();

		DB::table($_this->table)->insert( $data );

	}

	public static function edit_product_category( $data, $product_category_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)->where('id', "=", $product_category_id)->update( $data );

		return $result;

	}

	public static function get_child_product_category( $parents_id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select(
								"id",
								"name"
							)
					->where("parents_id", "=", $parents_id)
					->get();

		return $result;

	}

	public static function get_parents_category_id( $id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select(
								"parents_id"
							)
					->find( $id );

		return $result;

	}

	public static function get_all_parents_category()
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select("*")
					->get();

		return $result;

	}

	public static function get_mutli_parents_category_id( $id )
	{

		$_this = new self();

		$result = DB::table($_this->table)
					->select(
								"id",
								"parents_id"
							)
					->whereIn( "id", $id )
					->get();

		return $result;

	}

}
