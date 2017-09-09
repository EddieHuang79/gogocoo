<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class Mall
{

	protected $mall_shop_table = "mall_shop";

	protected $mall_shop_spec_table = "mall_shop_spec";

	protected $mall_product_table = "mall_product";

	protected $mall_product_rel_table = "mall_product_rel";

	protected $mall_record_table = "mall_record";


	public static function add_mall_shop( $data )
	{

		$_this = new self();

		$result = DB::table($_this->mall_shop_table)->insertGetId($data);

		return $result;

	}

	public static function edit_mall_shop( $data, $where )
	{

		$_this = new self();

		$result = DB::table($_this->mall_shop_table)->where('id', $where)->update($data);

		return $result;

	}

	public static function add_mall_product_spec( $data )
	{

		$_this = new self();

		$result = DB::table($_this->mall_shop_spec_table)->insert($data);

		return $result;

	}

	public static function del_mall_product_spec( $mall_shop_id )
	{

		$_this = new self();

		$result = DB::table($_this->mall_shop_spec_table)->where("mall_shop_id", "=", $mall_shop_id)->delete();

		return $result;

	}

	public static function add_child_product( $data )
	{

		$_this = new self();

		$result = DB::table($_this->mall_product_rel_table)->insert($data);

		return $result;

	}

	public static function del_child_product( $mall_shop_id )
	{

		$_this = new self();

		$result = DB::table($_this->mall_product_rel_table)->where("mall_shop_id", "=", $mall_shop_id)->delete();

		return $result;

	}

	public static function get_mall_list( $data )
	{

		$_this = new self();

		$result = DB::table($_this->mall_shop_table)
					->select($_this->mall_shop_table.".*", $_this->mall_shop_table.".cost");
					// ->leftJoin($_this->mall_product_rel_table, $_this->mall_shop_table.'.id', '=', $_this->mall_product_rel_table.'.mall_shop_id');

		// $result = isset($data["id"]) && !empty($data["id"]) ? $result->where($_this->mall_shop_table.".id", "=", $data["id"]) : $result ;
		$result = $result->orderBy($_this->mall_shop_table.".id","desc")->get();

		return $result;

	}

	public static function get_single_mall( $mall_shop_id )
	{

		$_this = new self();

		$spec_array = array();

		$result = DB::table($_this->mall_shop_table)->find( $mall_shop_id );

		$spec = DB::table($_this->mall_product_rel_table)->select("number", "date_spec")->where( "mall_shop_id", "=", $mall_shop_id )->get();

		foreach ($spec as $row) 
		{	
			$spec_array[] = $row;
		}

		$result->spec_id = $spec_array;

		return $result;

	}

	public static function get_mall_service_list()
	{

		$_this = new self();

		$result = DB::table($_this->mall_product_table)->get();

		return $result;

	}

	public static function get_mall_service_rel( $mall_shop_id )
	{

		$_this = new self();

		$result = DB::table($_this->mall_product_table)
					->select(
								$_this->mall_product_rel_table.".mall_shop_id",
								$_this->mall_product_rel_table.".mall_product_id",
								$_this->mall_product_table.".product_name",
								$_this->mall_product_rel_table.".date_spec",
								$_this->mall_product_rel_table.".number"
							)
					->leftJoin($_this->mall_product_rel_table, $_this->mall_product_table.'.id', '=', $_this->mall_product_rel_table.'.mall_product_id')
					->whereIn("mall_shop_id", $mall_shop_id)
					->get();

		return $result;

	}

	public static function get_mall_image( $mall_shop_id )
	{

		$_this = new self();

		$result = DB::table($_this->mall_shop_table)
					->select("pic")
					->find( $mall_shop_id );

		return $result;

	}

}
