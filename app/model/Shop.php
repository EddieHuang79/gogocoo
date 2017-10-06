<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Shop extends Model
{

	protected $table = "mall_shop";
	protected $spec_table = "mall_shop_spec";
	protected $record_table = "mall_record";
	protected $mall_product_table = "mall_product";
	protected $mall_product_rel_table = "mall_product_rel";
	protected $mall_product_use_table = "mall_product_use";
	protected $mall_pay_record_table = "mall_pay_record";


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
					->select(
							// $_this->mall_product_rel_table.".*",
							$_this->table.'.id',
							$_this->table.'.product_name',
							$_this->table.'.cost',
							$_this->record_table.'.store_id',
							$_this->record_table.'.paid_at',
							$_this->record_table.'.number',
							$_this->record_table.'.MerchantTradeNo',
							$_this->record_table.'.status',
							$_this->record_table.'.created_at'
						)
					->leftJoin($_this->table, $_this->record_table.'.mall_shop_id', '=', $_this->table.'.id')
					// ->leftJoin($_this->mall_product_rel_table, $_this->record_table.'.mall_shop_id', '=', $_this->mall_product_rel_table.'.mall_shop_id')
					->where($_this->record_table.".store_id", "=", $data["store_id"])
					->orderBy("created_at", "DESC")
					->paginate(15);

		return $data;

	}

	public static function get_mall_product( $mall_shop_id )
	{

		$_this = new self();

		$data = DB::table($_this->table)
					->select( $_this->table.".id", $_this->table.".product_name", $_this->table.".description", $_this->table.".pic", $_this->table.".cost" )
					// ->leftJoin($_this->spec_table, $_this->table.'.id', '=', $_this->spec_table.'.mall_shop_id')
					->where($_this->table.".id", "=", $mall_shop_id)
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

		$result = DB::table($_this->record_table)->insertGetId( $data );

		return $result;

	}

	// public static function get_mall_shop_id( $data )
	// {

	// 	$_this = new self();

	// 	$data = DB::table($_this->record_table)
	// 				->select(
	// 						$_this->record_table.".mall_shop_id",
	// 						"action_key",
	// 						"number"
	// 					)
	// 				->leftJoin($_this->table, $_this->record_table.'.mall_shop_id', '=', $_this->table.'.id')
	// 				->leftJoin($_this->mall_product_rel_table, $_this->mall_product_rel_table.'.mall_shop_id', '=', $_this->table.'.id')
	// 				->leftJoin($_this->mall_product_table, $_this->mall_product_rel_table.'.mall_product_id', '=', $_this->mall_product_table.'.id')
	// 				->where($_this->record_table.".user_id", "=", $data["user_id"])
	// 				->where($_this->mall_product_table.".action_key", "=", $data["action_key"])
	// 				->get();

	// 	return $data;

	// }

	// 取得購買紀錄

	public static function get_shop_record_by_id( $store_id, $action_key )
	{

		$_this = new self();

		$data = DB::table($_this->record_table)
					->select(
							// \DB::raw('SUM(number) as number')
							$_this->record_table.".mall_shop_id",
							$_this->mall_product_rel_table.".mall_product_id",
							$_this->mall_product_rel_table.".date_spec",
							$_this->mall_product_rel_table.".number",
							$_this->record_table.".number as buy_number"
						)
					->leftJoin($_this->table, $_this->record_table.'.mall_shop_id', '=', $_this->table.'.id')
					->leftJoin($_this->mall_product_rel_table, $_this->mall_product_rel_table.'.mall_shop_id', '=', $_this->table.'.id')
					->leftJoin($_this->mall_product_table, $_this->mall_product_rel_table.'.mall_product_id', '=', $_this->mall_product_table.'.id')
					->where($_this->record_table.".store_id", "=", $store_id)
					->where($_this->mall_product_table.".action_key", "=", $action_key)
					->where($_this->record_table.".status", "=", "1")
					// ->groupBy($_this->record_table.".mall_shop_id")
					->get();

		return $data;

	}

	// 取得使用紀錄表

	public static function get_mall_product_use_record( $store_id, $item_id, $action_key, $type )
	{

		$_this = new self();

		$data = DB::table($_this->mall_product_use_table)
					->select(
							$_this->mall_product_use_table.".active_item_id",
							$_this->record_table.".mall_shop_id",
							//$_this->record_table.".id",
							$_this->mall_product_use_table.".mall_product_id"
						)
					->leftJoin($_this->record_table, $_this->mall_product_use_table.'.mall_record_id', '=', $_this->record_table.'.id')
					->leftJoin($_this->mall_product_table, $_this->mall_product_use_table.'.mall_product_id', '=', $_this->mall_product_table.'.id')
					->where($_this->mall_product_use_table.".store_id", "=", $store_id);

		$data = !empty($item_id) ? $data->whereIn($_this->mall_product_use_table.".active_item_id", $item_id) : $data ;
		
		$data = !empty($action_key) ? $data->where($_this->mall_product_table.".action_key", "=", $action_key) : $data ;

		$data = !empty($type) ? $data->where($_this->mall_product_use_table.".type", "=", $type) : $data ;

		$data = $data->get();

		return $data;

	}

	// 驗證使用項目

	public static function check_legal( $store_id, $data )
	{

		$_this = new self();

		$data = DB::table($_this->mall_product_use_table)
					->select($_this->mall_product_use_table.".id")
					->leftJoin($_this->mall_product_rel_table, $_this->mall_product_rel_table.'.mall_shop_id', '=', $_this->mall_product_use_table.'.mall_shop_id')
					->where($_this->mall_product_use_table.".store_id", "=", $store_id)
					->where($_this->mall_product_use_table.".mall_shop_id", "=", $data[0])
					->where($_this->mall_product_use_table.".mall_product_id", $data[1])
					->where($_this->mall_product_rel_table.".date_spec", "=", $data[2])
					->where($_this->mall_product_use_table.".status", "=", "1")
					->first();
		
		$result = !empty($data) ? $data->id : 0 ;

		return $result;

	}

	// 寫入使用紀錄

	public static function add_use_record( $data )
	{

		$_this = new self();

		return DB::table($_this->mall_product_use_table)->insert($data);

	}

	// 寫入使用紀錄

	public static function get_mall_product_rel_data()
	{

		$_this = new self();

		return DB::table($_this->mall_product_rel_table)->get();

	}

	// 啟用服務

	public static function active_use_record( $data, $use_id )
	{

		$_this = new self();

		return DB::table($_this->mall_product_use_table)->where("id", "=", $use_id)->update($data);

	}

	// 驗證使用項目

	public static function get_valuable_id( $store_id, $type )
	{

		$_this = new self();

		$result = DB::table($_this->mall_product_use_table)
					->select($_this->mall_product_use_table.".active_item_id")
					->where($_this->mall_product_use_table.".store_id", "=", $store_id)
					->where($_this->mall_product_use_table.".type", "=", $type)
					->where($_this->mall_product_use_table.".mall_product_id", "!=", "2")
					->get();
		
		return $result;

	}

	// 取得未使用的選項

	public static function get_not_use_extend( $store_id, $action_key )
	{

		$_this = new self();

		$data = DB::table($_this->mall_product_use_table)
					->select(
							$_this->mall_product_use_table.".active_item_id",
							$_this->record_table.".mall_shop_id",
							$_this->mall_product_use_table.".mall_product_id"
						)
					->leftJoin($_this->record_table, $_this->mall_product_use_table.'.mall_record_id', '=', $_this->record_table.'.id')
					->leftJoin($_this->mall_product_table, $_this->mall_product_use_table.'.mall_product_id', '=', $_this->mall_product_table.'.id')
					->where($_this->mall_product_use_table.".store_id", "=", $store_id)
					->where($_this->mall_product_table.".action_key", "=", $action_key)
					->where($_this->mall_product_use_table.".status", "=", "1")
					->get();

		return $data;

	}

	public static function get_record_cnt( $store_id )
	{

		$_this = new self();

		$data = DB::table($_this->record_table)
					->select("MerchantTradeNo")
					->where("store_id", "=", $store_id)
					->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])
					->orderBy("MerchantTradeNo", "desc")
					->first();

		return $data;

	}


    public static function add_payment_data( $data )
    {

		$_this = new self();

		return DB::table($_this->mall_pay_record_table)->insertGetId( $data );

    }


	public static function get_payment_data( $payment_id )
	{

		$_this = new self();

		$data = DB::table($_this->mall_pay_record_table)
					->select(
						"MerchantTradeNo",
						"PaymentDate"
					)
					->where("RtnCode", "=", "1")
					->where("id", "=", $payment_id)
					->first();

		return $data;

	}


    public static function active_mall_service( $data )
    {

		$_this = new self();

		DB::table($_this->record_table)
			->where("store_id", "=", $data["store_id"])
			->where("MerchantTradeNo", "=", $data["seq"])
			->update( array( "status" => 1, "paid_at" => $data["PaymentDate"] ) );

    }


    public static function get_single_record_data( $id )
    {

		$_this = new self();

		return DB::table($_this->record_table)->select("*")->find( $id );

    }

}
