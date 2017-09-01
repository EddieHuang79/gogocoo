<?php

namespace App\logic;

use Illuminate\Database\Eloquent\Model;
use App\model\Mall;

class Mall_logic extends Basetool
{

	protected $txt;

	public function __construct()
	{

		// 文字
		$this->txt = Web_cht::get_txt();

	}

	// 新增格式
	public static function insert_format( $data )
	{

		$_this = new self();

		$result = array(
			"product_name"      => isset($data["product_name"]) ? $_this->strFilter($data["product_name"]) : "",
			"description"       => isset($data["description"]) ? trim($data["description"]) : "",
			"pic"          		=> isset($data["product_image_path"]) ? trim($data["product_image_path"]) : "",
			"public"          	=> isset($data["public"]) ? intval($data["public"]) : "",
			"start_date"       	=> isset($data["start_date"]) ? date("Y-m-d 00:00:00", strtotime($data["start_date"])) : "",
			"end_date"         	=> isset($data["end_date"]) ? date("Y-m-d 23:59:59", strtotime($data["end_date"])) : "",
			"created_at"    	=> date("Y-m-d H:i:s"),
			"updated_at"    	=> date("Y-m-d H:i:s")
			);

		return $result;

	}

	// 新增規格格式
	public static function insert_spec_format( $data )
	{

		$_this = new self();

		$result = array();

		$data["cost"] = array_filter($data["cost"], "intval");

		$data["date_range"] = array_filter($data["date_range"], "intval");

		$cnt = count($data["cost"]);

		for ($i=0; $i < $cnt; $i++) 
		{ 
			$result[] = array(
				"mall_shop_id"   	=> isset($data["mall_shop_id"]) ? intval($data["mall_shop_id"]) : "",
				"cost"          	=> isset($data["cost"][$i]) ? intval($data["cost"][$i]) : "",
				"date_spec"         => isset($data["date_range"][$i]) ? intval($data["date_range"][$i]) : "",
				);
		}

		return $result;

	}

	// 新增商品關聯
	public static function insert_child_product_format( $data )
	{

		$_this = new self();

		$result = array();

		$data["service"] = array_filter($data["service"], "intval");

		$cnt = count($data["service"]);

		for ($i=0; $i < $cnt; $i++) 
		{ 
			$result[] = array(
					"mall_shop_id"   	=> isset($data["mall_shop_id"]) ? intval($data["mall_shop_id"]) : "",
					"mall_product_id"   => isset($data["service"][$i]) ? intval($data["service"][$i]) : "",
				);
		}

		return $result;

	}

	// 更新格式
	public static function update_format( $data )
	{

		$_this = new self();

		$result = array(
					"product_name"      => isset($data["product_name"]) ? $_this->strFilter($data["product_name"]) : "",
					"description"       => isset($data["description"]) ? trim($data["description"]) : "",
					"public"          	=> isset($data["public"]) ? intval($data["public"]) : "",
					"start_date"       	=> isset($data["start_date"]) ? date("Y-m-d 00:00:00", strtotime($data["start_date"])) : "",
					"end_date"         	=> isset($data["end_date"]) ? date("Y-m-d 23:59:59", strtotime($data["end_date"])) : "",
					"updated_at"    	=> date("Y-m-d H:i:s")
		         );

		if ( isset($data["product_image_path"]) && !empty($data["product_image_path"]) ) 
		{
			$result["pic"] = trim($data["product_image_path"]);
		}

		return $result;

	}

	// 新增產品
	public static function add_mall_shop( $data )
	{

		return Mall::add_mall_shop( $data );

	}

	// 修改訊息
	public static function edit_mall_shop( $data, $mall_shop_id )
	{

		return Mall::edit_mall_shop( $data, $mall_shop_id );

	}

	// 新增規格
	public static function add_mall_product_spec( $data )
	{

		return Mall::add_mall_product_spec( $data );

	}

	// 刪除規格
	public static function del_mall_product_spec( $mall_shop_id )
	{

		return Mall::del_mall_product_spec( $mall_shop_id );

	}

	// 新增子商品
	public static function add_child_product( $data )
	{

		return Mall::add_child_product( $data );

	}

	// 刪除子商品
	public static function del_child_product( $mall_shop_id )
	{

		return Mall::del_child_product( $mall_shop_id );

	}

	// 產品列表
	public static function get_mall_list( $data )
	{

		$_this = new self();

		$txt = $_this->txt;

		$mall_shop_id = array();
		$spec = array();
		$result = array();

		// 產品
		$mall = Mall::get_mall_list( $data );

		foreach ($mall as &$row) 
		{

			$mall_shop_id[] = $row->id;
			$spec[$row->id][] = $txt["cost_unit"] . $row->cost . "/" . $row->date_spec . $txt["day_unit"] ; 
			$row->public_txt = $row->public > 0 ? $txt["yes"] : $txt["no"] ;
			$row->start_date_desc = strtotime($row->start_date) > 0 ? $row->start_date : $txt["product_on_right_now"] ;
			$row->end_date_desc = strtotime($row->end_date) > strtotime("1970-01-01 23:59:59") ? $row->end_date : $txt["product_on_forever"] ;

		}

		// 關聯服務
		$mall_rel = $_this->get_mall_service_rel( $mall_shop_id );

		foreach ($mall as &$row) 
		{
			
			$row->spec = isset($spec[$row->id]) ? $spec[$row->id] : array($txt["product_spec_not_setting"]) ; 

			if ( !array_key_exists( $row->id , $result ) ) 
			{
				$result[$row->id] = $row;
			}

			$row->include_service = isset($mall_rel[$row->id]) ? $mall_rel[$row->id] : array() ;
		
		}

		return $result;

	}

	// 取得單一產品
	public static function get_single_mall( $mall_id )
	{

		$_this = new self();

		$txt = $_this->txt;

		$spec = array();
		$result = array();

		// 產品
		$result = Mall::get_single_mall( $mall_id );

		return $result;

	}


	// 取得服務清單
	public static function get_mall_service_list()
	{

		$_this = new self();

		$result = array();

		$data = Mall::get_mall_service_list();

		foreach ($data as $row) 
		{
			
			$result[$row->id] = $row->product_name;

		}

		return $result;

	}

	// 取得商品服務關聯
	public static function get_mall_service_rel( $mall_shop_id )
	{

		$_this = new self();

		$result = array();

		$data = Mall::get_mall_service_rel( $mall_shop_id );

		if ( $data->count() > 0 ) 
		{

			foreach ($data as $row) 
			{
				
				$result[$row->mall_shop_id][$row->mall_product_id] = $row->product_name;

			}

		}

		return $result;

	}

}
