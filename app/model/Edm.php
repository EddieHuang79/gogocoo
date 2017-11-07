<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class Edm
{

	protected $table = "edm";

	protected $page_size = 15;

	public static function add_edm( $data )
	{

		$_this = new self;

		$result = DB::table($_this->table)->insertGetId($data);

		return $result;

	}

	public static function edit_edm( $data, $where )
	{

		$_this = new self;

		$result = DB::table($_this->table)->where('id', $where)->update($data);

		return $result;

	}


	public static function get_edm_list( $data )
	{

		$_this = new self;

		$result = DB::table($_this->table);

		$result = !empty($data["status"]) ? $result->whereIn("status", $data["status"]) : $result ;

		$result = $result->orderBy('id', 'desc')->paginate($_this->page_size);

		return $result;

	}

	public static function get_single_edm( $edm_id )
	{

		$_this = new self;

		$result = DB::table($_this->table)->find($edm_id);

		return $result;

	}	

	public static function change_status( $edm_id, $status )
	{

		$_this = new self();

		$result = DB::table($_this->table)->whereIn("id", $edm_id)->update( array("status" => $status, "updated_at" => date("Y-m-d H:i:s")) );

	}

	public static function get_edm_to_send()
	{

		$_this = new self;

		$result = DB::table($_this->table)
					->where("status", "=", "2")
					->where("send_time", "<=", date("Y-m-d H:i:s"))
					->orderBy("send_time")
					->first();

		return $result;

	}

}
