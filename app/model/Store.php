<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
 
	protected $table = "store";

	protected $user = "user";

	protected $page_size = 15;

	public static function get_store_cnt( $user_id )
	{
	     
		$_this = new self();

		$parents_data = DB::table($_this->user)->select("parents_id")->where("id", "=", $user_id)->first();

		$find_id = (int)$parents_data->parents_id > 0 ? (int)$parents_data->parents_id : $user_id ;

		$result = DB::table($_this->table)->where("user_id", "=", $find_id)->get()->count();

		return $result;

	}	

	public static function get_store_info( $user_id )
	{
	     
		$_this = new self();

		$parents_data = DB::table($_this->user)->select("parents_id")->where("id", "=", $user_id)->first();

		$find_id = (int)$parents_data->parents_id > 0 ? (int)$parents_data->parents_id : $user_id ;

		$result = DB::table($_this->table)->where("user_id", "=", $find_id)->orderBy("id", "asc")->get();

		return $result;

	}	

	public static function edit_store( $data, $where )
	{

		$_this = new self;

		$result = DB::table($_this->table)->where('id', $where)->update($data);

		return $result;

	}

	public static function add_store( $data )
	{

		$_this = new self;

		$result = DB::table($_this->table)->insertGetId($data);

		return $result;

	}

	public static function get_store_list( $data )
	{

		$_this = new self;

		$result = DB::table($_this->table)
				->where('user_id', '=', $data["user_id"])
				->orderBy('id', 'asc')
				->paginate($_this->page_size);

		return $result;

	}	

	public static function get_single_store( $store_id )
	{

		$_this = new self;

		$result = DB::table($_this->table)->find($store_id);

		return $result;

	}	

	public static function get_store_id_by_code( $store_code )
	{
	     
		$_this = new self();

		$result = DB::table($_this->table)->select("id")->where("store_code", "=", $store_code)->first();

		return $result;

	}	

	public static function get_rel_shop_id( $user_id_array )
	{

		$_this = new self;

		$result = DB::table($_this->table)->whereIn("user_id", $user_id_array)->get();

		return $result;

	}

	public static function get_all_store()
	{

		$_this = new self;

		$result = DB::table($_this->table)->get();

		return $result;

	}

}
