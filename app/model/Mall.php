<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class Mall
{

	protected $mall_product_table = "mall_product";

	protected $mall_product_spec_table = "mall_product_spec";
	
	protected $mall_product_record_table = "mall_product_record";

	public static function add_mall_product( $data )
	{

		$_this = new self;

		$result = DB::table($_this->mall_product_table)->insertGetId($data);

		return $result;

	}

	public static function edit_mall_product( $data, $where )
	{

		$_this = new self;

		$result = DB::table($_this->mall_product_table)->where('id', $where)->update($data);

		return $result;

	}

	public static function add_mall_product_spec( $data )
	{

		$_this = new self;

		$result = DB::table($_this->mall_product_spec_table)->insert($data);

		return $result;

	}

	public static function del_mall_product_spec( $mall_product_id )
	{

		$_this = new self;

		$result = DB::table($_this->mall_product_spec_table)->where("mall_product_id", "=", $mall_product_id)->delete();

		return $result;

	}

	public static function get_mall_list( $data )
	{

		$_this = new self;

		$result = DB::table($_this->mall_product_table)
					->select($_this->mall_product_table.".*", $_this->mall_product_spec_table.".cost", $_this->mall_product_spec_table.".date_spec")
					->leftJoin($_this->mall_product_spec_table, $_this->mall_product_table.'.id', '=', $_this->mall_product_spec_table.'.mall_product_id');

		// $result = isset($data["id"]) && !empty($data["id"]) ? $result->where($_this->mall_product_table.".id", "=", $data["id"]) : $result ;
		$result = $result->get();

		return $result;

	}

	public static function get_single_mall( $mall_id )
	{

		$_this = new self;

		$spec_array = array();

		$result = DB::table($_this->mall_product_table)->find( $mall_id );

		$spec = DB::table($_this->mall_product_spec_table)->select("cost", "date_spec")->where( "mall_product_id", "=", $mall_id )->get();

		foreach ($spec as $row) 
		{	
			$spec_array[] = $row;
		}

		$result->spec_id = $spec_array;

		return $result;

	}

}
