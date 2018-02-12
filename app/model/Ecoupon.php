<?php

namespace App\model;

use Illuminate\Support\Facades\DB;

class Ecoupon
{

    protected $table = "ecoupon";

    protected $ecoupon_user_table = "ecoupon_user_list";
    
    protected $ecoupon_use_record_table = "ecoupon_use_record";

    protected $page_size = 15;


	public static function add_ecoupon( $data )
	{

		$_this = new self;

		$result = DB::table($_this->table)->insertGetId($data);

		return $result;

	}

	public static function edit_ecoupon( $data, $where )
	{

		$_this = new self;

		$result = DB::table($_this->table)->where('id', $where)->update($data);

		return $result;

	}

	public static function get_ecoupon_list( $data )
	{

		$_this = new self;

		$result = DB::table($_this->table);

		$result = !empty($data["status"]) ? $result->whereIn("status", $data["status"]) : $result ;

		$result = $result->orderBy('id', 'desc')->paginate($_this->page_size);

		return $result;

	}

	public static function get_single_ecoupon( $ecoupon_id )
	{

		$_this = new self;

		$result = DB::table($_this->table)->find($ecoupon_id);

		return $result;

	}

	public static function get_active_ecoupon( $send_type )
	{

		$_this = new self;

		$result = DB::table($_this->table)
					->where("status", "=", "1")
					->where("send_type", "=", $send_type)
					->whereRaw("'" . date('Y-m-d H:i:s') . "' BETWEEN `start_date` AND `end_date`")
					->get();

		return $result;

	}

	public static function get_ecoupon_user_list( $ecoupon_id )
	{

		$_this = new self;

		$result = DB::table($_this->ecoupon_user_table)
					->where("ecoupon_id", "=", $ecoupon_id)
					->get();

		return $result;

	}

	public static function add_ecoupon_user_list( $data )
	{

		$_this = new self;

		$result = DB::table($_this->ecoupon_user_table)->insertGetId($data);

		return $result;

	}

	public static function get_user_active_ecoupon( $store_id )
	{

		$_this = new self;

		$result = DB::table($_this->table)
					->leftJoin($_this->ecoupon_user_table, $_this->table.'.id', '=', $_this->ecoupon_user_table.'.ecoupon_id')
					->where($_this->table.".status", "=", "1")
					->whereRaw("'" . date('Y-m-d H:i:s') . "' BETWEEN `ecoupon_active_date` AND `deadline`")
					->where($_this->ecoupon_user_table.".store_id", "=", $store_id)
					->where($_this->ecoupon_user_table.".status", "=", "1")
					->orderBy('deadline', 'desc')
					->get();


		// $result = $result->orderBy('id', 'desc')->paginate($_this->page_size);

		return $result;

	}

	public static function get_expiring_ecoupon( $date )
	{

		$_this = new self;

		$result = DB::table($_this->table)
					->leftJoin($_this->ecoupon_user_table, $_this->table.'.id', '=', $_this->ecoupon_user_table.'.ecoupon_id')
					->where($_this->table.".status", "=", "1")
					->whereRaw("'" . $date . "' >= `deadline`")
					->where($_this->ecoupon_user_table.".status", "=", "1")
					->orderBy('deadline', 'desc')
					->get();


		// $result = $result->orderBy('id', 'desc')->paginate($_this->page_size);

		return $result;

	}

	public static function add_ecoupon_use_record( $data )
	{

		$_this = new self;

		$result = DB::table($_this->ecoupon_use_record_table)->insertGetId($data);

		return $result;

	}

	public static function inactive_ecoupon_use_status( $code )
	{

		$_this = new self;

		$result = DB::table($_this->ecoupon_user_table)->where('code', $code)->update( array( "status" => 2 ) );

		return $result;

	}

	public static function get_ecoupon_full_data( $code )
	{

		$_this = new self;

		$result = DB::table($_this->table)
					->select(
							$_this->table.".*",
							$_this->ecoupon_user_table.".id as ecoupon_use_id",
							$_this->ecoupon_user_table.".store_id",
							$_this->ecoupon_user_table.".status as user_status"
						)
					->leftJoin($_this->ecoupon_user_table, $_this->table.'.id', '=', $_this->ecoupon_user_table.'.ecoupon_id')
					->where($_this->ecoupon_user_table.".code", "=", $code)
					->get();


		// $result = $result->orderBy('id', 'desc')->paginate($_this->page_size);

		return $result;

	}

	public static function get_ecoupon_use_record( $store_id )
	{

		$_this = new self;

		$result = DB::table($_this->ecoupon_use_record_table)
					->select($_this->ecoupon_use_record_table.".record_id", $_this->ecoupon_use_record_table.".discount")
					->leftJoin($_this->ecoupon_user_table, $_this->ecoupon_use_record_table.'.ecoupon_use_id', '=', $_this->ecoupon_user_table.'.id')
					->leftJoin($_this->table, $_this->table.'.id', '=', $_this->ecoupon_user_table.'.ecoupon_id')
					->where($_this->ecoupon_use_record_table.".store_id", "=", $store_id)
					->where($_this->ecoupon_user_table.".store_id", "=", $store_id)
					->get();

		return $result;

	}

	public static function get_user_ecoupon_data( $store_id )
	{

		$_this = new self;

		$result = DB::table($_this->table)
					->leftJoin($_this->ecoupon_user_table, $_this->table.'.id', '=', $_this->ecoupon_user_table.'.ecoupon_id')
					->leftJoin($_this->ecoupon_use_record_table, $_this->ecoupon_user_table.'.id', '=', $_this->ecoupon_use_record_table.'.ecoupon_use_id')
					->select(
							$_this->table . ".name",
							$_this->table . ".type",
							$_this->table . ".ecoupon_content",
							$_this->table . ".deadline",
							$_this->ecoupon_user_table . ".status",
							$_this->ecoupon_use_record_table . ".created_at"
						)
					->where($_this->ecoupon_user_table.".store_id", "=", $store_id)
					->orderBy('deadline', 'desc')
					->get();


		return $result;

	}

}
