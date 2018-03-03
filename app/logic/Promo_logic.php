<?php

namespace App\logic;

use App\model\Promo;

class Promo_logic extends Basetool
{

	protected $status_txt = array();


	public function __construct()
	{

		$txt = Web_cht::get_txt();

		$this->status_txt = array(
								1 	=>	$txt["enable"],
								2 	=>	$txt["disable"]
							);

	}	


	// 新增格式

	public static function insert_format( $data )
	{
	     
		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
							"mall_shop_id"      => isset($data["mall_shop_id"]) ? intval($data["mall_shop_id"]) : "",
							"status"          	=> isset($data["status"]) ? intval($data["status"]) : "",
							"cost"         		=> isset($data["cost"]) ? intval($data["cost"]) : "",
							"start_date"       	=> isset($data["start_date"]) ? date("Y-m-d 00:00:00", strtotime($data["start_date"])) : "",
							"end_date"         	=> isset($data["end_date"]) ? date("Y-m-d 23:59:59", strtotime($data["end_date"])) : "",
							"created_at"    	=> date("Y-m-d H:i:s"),
							"updated_at"    	=> date("Y-m-d H:i:s")
			            );

		}

		return $result;

	}


	// 更新格式

	public static function update_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
							"mall_shop_id"      => isset($data["mall_shop_id"]) ? intval($data["mall_shop_id"]) : "",
							"status"          	=> isset($data["status"]) ? intval($data["status"]) : "",
							"cost"         		=> isset($data["cost"]) ? intval($data["cost"]) : "",
							"start_date"       	=> isset($data["start_date"]) ? date("Y-m-d 00:00:00", strtotime($data["start_date"])) : "",
							"end_date"         	=> isset($data["end_date"]) ? date("Y-m-d 23:59:59", strtotime($data["end_date"])) : "",
							"updated_at"    => date("Y-m-d H:i:s")
			            );

		}

		return $result;

	}


	// 新增促銷價

	public static function add_promo_data( $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) ) 
		{

			$result = Promo::add_promo_data( $data );

		}

		return $result;

	}


	// 修改促銷價

	public static function edit_promo_data( $data, $promo_id )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($promo_id) && is_int($promo_id) ) 
		{

			Promo::edit_promo_data( $data, $promo_id );

			$result = true;

		}

		return $result;

	}


	// 取得促銷價

	public static function get_promo_price( $mall_shop_id )
	{

		$_this = new self();

		$result = array();

		$status_txt = $_this->status_txt;

		if ( !empty($mall_shop_id) && is_int($mall_shop_id) ) 
		{

			$data = Promo::get_promo_price( $mall_shop_id );

			if ( $data->isNotEmpty() ) 
			{

				foreach ($data as $row) 
				{
				
					$result[] = array(
									"id" 			=> $row->id,
									"cost" 			=> $row->cost,
									"start_date" 	=> $row->start_date,
									"end_date" 		=> $row->end_date,
									"status" 		=> $row->status,
									"status_txt" 	=> isset($status_txt[$row->status]) ? $status_txt[$row->status] : "" ,
 								);

				}
			
			}

		}

		return $result;

	}


	// 取得單筆資料

	public static function get_single_promo_data( $id )
	{

		$result = array();

		if ( !empty($id) && is_int($id) ) 
		{

			$result = Promo::get_single_promo_data( $id );

		}

		return $result;

	}


	// 判斷日期是否重複

	public static function is_promo_date_repeat( $data, $except_id = 0 )
	{

		$result = false;

		if ( !empty($data) && $data["status"] === 1 ) 
		{

			$data = Promo::is_promo_date_repeat( $data, $except_id );

			$result = $data->isNotEmpty() ? true : false ;

		}

		return $result;

	}

	// 取得促銷價

	public static function get_active_promo_price( $mall_shop_id )
	{

		$_this = new self();

		$result = 0;

		if ( !empty($mall_shop_id) && is_int($mall_shop_id) ) 
		{

			$data = Promo::get_active_promo_price( $mall_shop_id );

			$result = !empty($data) ? $data->cost : 0 ;

		}

		return $result;

	}


	// 取得輸入邏輯陣列

	public static function get_promo_input_template_array()
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$htmlData = array(
					"mall_shop_id" => array(
						"type"          => 11, 
						"title"         => "",
						"key"           => "mall_shop_id",
						"value"         => "" ,
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "hide",
						"hasPlugin"     => "",
						"placeholder"   => ""
					),
					"product_name" => array(
						"type"          => 1, 
						"title"         => $txt["promo_price"],
						"key"           => "cost",
						"value"         => "" ,
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => $txt['promo_price_input']
					),
					"status" => array(
						"type"          => 3,
						"title"         => $txt["status"],
						"key"           => "status",
						"value"         => "",
						"data"          => array(
												1 => $txt["enable"],
												2 => $txt["disable"]
											),
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => ""
					),
					"start_date" => array(
						"type"          => 1, 
						"title"         => $txt["start_date"],
						"key"           => "start_date",
						"value"         => "",
						"display"       => true,
						"desc"          => "",
						"EventFunc"     => "",
						"attrClass"     => "",
						"hasPlugin"     => "DateTimePicker"
					),
					"end_date" => array(
						"type"          => 1, 
						"title"         => $txt["end_date"],
						"key"           => "end_date",
						"value"         => "",
						"data"          => "",
						"display"       => true,
						"desc"          => "",
						"EventFunc"     => "",
						"attrClass"     => "",
						"hasPlugin"     => "DateTimePicker",
						"placeholder"   => ""
					)
		         );

		return $htmlData;

	}


	// 組合資料

	public static function promo_input_data_bind( $htmlData, $OriData )
	{

		$_this = new self();

		$result = $htmlData;

		if ( !empty($OriData) && is_array($OriData) ) 
		{

			foreach ($htmlData as &$row) 
			{

				if ( is_array($row) ) 
				{

				   $row["value"] = isset($OriData[$row["key"]]) ? $OriData[$row["key"]] : "" ;
				   
				}

			}

		}

		return $htmlData;

	}


	// 組合列表資料

	public static function promo_list_data_bind( $OriData )
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$result = array(
		             "title" => array(
		                         $txt['promo_price'],
		                         $txt['status'],
		                         $txt['start_date'],
		                         $txt['end_date'],
		                         $txt['action']
		                      ),
		             "data" => array()
		         );

		if ( !empty($OriData) && is_array($OriData) ) 
		{

			foreach ($OriData as $row) 
			{

				if ( is_array($row) ) 
				{

				   $data = array(
				            "data" => array(
				                        "promo_price"        => $txt["cost_unit"] . " " . $row['cost'],
				                        "status"        	 => $row['status_txt'],
				                        "start_date"         => $row['start_date'],
				                        "end_date"         	 => $row['end_date']
				                     ),
				            "Editlink" => "/promo/" . $row["id"] . "/edit?"
				         );
				   
				}

				$result["data"][] = $data;

			}

		}

		return $result;

	}

}
