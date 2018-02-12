<?php

namespace App\logic;

use App\model\Ecoupon;
use App\logic\Web_cht;
use App\logic\Option_logic;
use App\logic\Store_logic;
use Illuminate\Support\Facades\Session;

class Ecoupon_logic extends Basetool
{

	protected $ecoupon_type = array(
								1 => "折扣券",
								2 => "代金券",
								3 => "滿減券"
							);

	protected $ecoupon_match_type = array(
									1 => "所有用戶",
									2 => "新用戶",
									3 => "特定類別用戶",
									4 => "指定用戶"
								);
	
	protected $ecoupon_send_type = array(
									1 => "登入"
								);


	protected $status = array();

	public function __construct()
	{

		// 文字

		$txt = Web_cht::get_txt();

		$this->status = array(
							1 	=>	$txt["enable"],
							2 	=>	$txt["disable"],
							3 	=>	$txt["verify"],
							4 	=>	$txt["cancel"]
						);

	}	

	// 檢驗資料

	public static function test_data( $data )
	{

		$_this = new self();

		$result = array(
					"result" 	=> false,
					"msg" 		=> array()
				);

		$error_msg = array();

		if ( !empty($data) && is_array($data) ) 
		{

            try
            {

				if ( (int)$data["type"] === 1 && empty($data["ecoupon_content_type1"]) ) 
				{

					$error_msg[] = "未填寫折扣數";
					
				}

				if ( (int)$data["type"] === 1 && !empty($data["ecoupon_content_type1"]) && ( $data["ecoupon_content_type1"] < 0 || $data["ecoupon_content_type1"] > 1 )  ) 
				{

					$error_msg[] = "折扣數輸入錯誤，小數需介於0-1之間";
					
				}


				if ( (int)$data["type"] === 2 && empty($data["ecoupon_content_type2"]) ) 
				{

					$error_msg[] = "未填寫折抵金額";
					
				}

				if ( (int)$data["type"] === 2 && !empty($data["ecoupon_content_type1"]) && intval($data["ecoupon_content_type1"]) < 1 ) 
				{

					$error_msg[] = "折抵金額輸入錯誤，請輸入大於0的數字";
					
				}

				if ( (int)$data["type"] === 3 && ( empty($data["ecoupon_content_type3"]["A"][0]) || empty($data["ecoupon_content_type3"]["B"][0]) ) ) 
				{

					$error_msg[] = "未填寫折價券內容";
					
				}

				if ( (int)$data["type"] === 3 && !empty($data["ecoupon_content_type3"]["A"][0]) && !empty($data["ecoupon_content_type3"]["B"][0]) ) 
				{

					$tmp_result = true;

					$tmp_num = -1;

					foreach ($data["ecoupon_content_type3"]["A"] as $row) 
					{

						$value = intval($row);

						if ( $tmp_num >= $value ) 
						{

							$tmp_result = false;

							break;
							
						}
						else
						{

							$tmp_num = $value;

						}

					}

					if ( $tmp_result === false ) 
					{

						$error_msg[] = "欄位A金額級距填寫錯誤，金額必須由小至大";
						
					}

				}

				if ( (int)$data["match_type"] === 3 && empty($data["parents_store_type"]) ) 
				{

					$error_msg[] = "未選擇贈送類別";
					
				}

				if ( (int)$data["match_type"] === 4 && empty($data["match_id"]) ) 
				{

					$error_msg[] = "未填寫贈送對象";
					
				}

				if ( strtotime($data["start_date"]) > strtotime($data["end_date"]) ) 
				{

					$error_msg[] = "開始日期不可大於結束日期";
					
				}

				if ( strtotime($data["ecoupon_active_date"]) > strtotime($data["deadline"]) ) 
				{

					$error_msg[] = "生效日期不可大於截止日期";
					
				}

				if ( (int)$data["max_num"] < 0 ) 
				{

					$error_msg[] = "上限數量最小為0";
					
				}

                if ( !empty($error_msg) ) 
                {

                    throw new \Exception( json_encode($error_msg) );
                
                }

            }
            catch(\Exception $e)
            {

            	$msg = json_decode($e->getMessage() ,true);

				$result = array(
							"result" 	=> true,
							"msg" 		=> $msg
						);

            }

		}

		return $result;

	}


	// 新增格式

	public static function insert_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
				"type"     				=> isset($data["type"]) ? intval($data["type"]) : 1,
				"name"       			=> isset($data["name"]) ? trim($data["name"]) : "",
				"description"       	=> isset($data["description"]) ? trim($data["description"]) : "",
				"match_type"     		=> isset($data["match_type"]) ? intval($data["match_type"]) : 1,
				"send_type"     		=> isset($data["send_type"]) ? intval($data["send_type"]) : 1,
				"max_num"     			=> isset($data["max_num"]) ? intval($data["max_num"]) : 1,
				"ecoupon_active_date"   => isset($data["ecoupon_active_date"]) ? date("Y-m-d H:i:s", strtotime($data["ecoupon_active_date"])) : date("Y-m-d 00:00:00"),
				"deadline"     			=> isset($data["deadline"]) ? date("Y-m-d H:i:s", strtotime($data["deadline"])) : date("Y-m-d 23:59:59"),
				"start_date"     		=> isset($data["start_date"]) ? date("Y-m-d H:i:s", strtotime($data["start_date"])) : date("Y-m-d 00:00:00"),
				"end_date"     			=> isset($data["end_date"]) ? date("Y-m-d H:i:s", strtotime($data["end_date"])) : date("Y-m-d 23:59:59"),
				"status"        		=> isset($data["active"]) ? intval($data["active"]) : 1,
				"created_at"    		=> date("Y-m-d H:i:s"),
				"updated_at"    		=> date("Y-m-d H:i:s")
			);

			$key = "ecoupon_content_type" . $result["type"];

			switch ($result["type"]) 
			{
				case 1:
				case 2:

					$result["ecoupon_content"] = isset($data[$key]) ? trim($data[$key]) : "";
				
					break;
				
				case 3:

					$result["ecoupon_content"] = isset($data[$key]) ? json_encode($data[$key]) : "";

					break;
			}

			if ( (int)$data["match_type"] === 3 ) 
			{

				$result["match_id"] = $data["parents_store_type"];
				
			}

			if ( (int)$data["match_type"] === 4 ) 
			{

				$result["match_id"] = isset($data["match_id"][0]) ? intval($data["match_id"][0]) : "";
				
			}				
				

		}

		return $result;

	}


	// 修改格式

	public static function update_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
				"type"     				=> isset($data["type"]) ? intval($data["type"]) : 1,
				"name"       			=> isset($data["name"]) ? trim($data["name"]) : "",
				"description"       	=> isset($data["description"]) ? trim($data["description"]) : "",
				"match_type"     		=> isset($data["match_type"]) ? intval($data["match_type"]) : 1,
				"send_type"     		=> isset($data["send_type"]) ? intval($data["send_type"]) : 1,
				"max_num"     			=> isset($data["max_num"]) ? intval($data["max_num"]) : 1,
				"ecoupon_active_date"   => isset($data["ecoupon_active_date"]) ? date("Y-m-d H:i:s", strtotime($data["ecoupon_active_date"])) : date("Y-m-d 00:00:00"),				
				"deadline"     			=> isset($data["deadline"]) ? date("Y-m-d H:i:s", strtotime($data["deadline"])) : date("Y-m-d 23:59:59"),
				"start_date"     		=> isset($data["start_date"]) ? date("Y-m-d H:i:s", strtotime($data["start_date"])) : date("Y-m-d 00:00:00"),
				"end_date"     			=> isset($data["end_date"]) ? date("Y-m-d H:i:s", strtotime($data["end_date"])) : date("Y-m-d 23:59:59"),
				"status"        		=> isset($data["active"]) ? intval($data["active"]) : 1,
				"updated_at"    		=> date("Y-m-d H:i:s")
			);

			$key = "ecoupon_content_type" . $result["type"];

			switch ($result["type"]) 
			{
				case 1:
				case 2:

					$result["ecoupon_content"] = isset($data[$key]) ? trim($data[$key]) : "";
				
					break;
				
				case 3:

					$result["ecoupon_content"] = isset($data[$key]) ? json_encode($data[$key]) : "";

					break;
			}
		
			if ( isset($data["match_id"][$data["match_type"]]) ) 
			{
				
				if ( (int)$data["match_type"] === 3 ) 
				{

					$result["match_id"] = $data["parents_store_type"];
					
				}

				if ( (int)$data["match_type"] === 4 ) 
				{

					$result["match_id"] = isset($data["match_id"][$data["match_type"]]) ? intval($data["match_id"][$data["match_type"]]) : "";
					
				}				
				
			}

		}

		return $result;
	}


	// 寫入ecoupon

	public static function add_ecoupon( $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) ) 
		{

			$result = Ecoupon::add_ecoupon( $data );

			$result = true;

		}

		return $result ;
	     
	}


	// 修改ecoupon

	public static function edit_ecoupon( $data, $ecoupon_id )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($ecoupon_id) && is_int($ecoupon_id) ) 
		{

			Ecoupon::edit_ecoupon( $data, $ecoupon_id );

			$result = true;

		}

		return $result;
	     
	}


	// 取得Ecoupon類別

	public static function get_ecoupon_type()
	{

		$_this = new self();

		return $_this->ecoupon_type;

	}


	// 取得Ecoupon適用類別

	public static function get_ecoupon_match_type()
	{

		$_this = new self();

		return $_this->ecoupon_match_type;

	}


	// 取得Ecoupon發送類別

	public static function get_ecoupon_send_type()
	{

		$_this = new self();

		return $_this->ecoupon_send_type;

	}


	// 列表

	public static function get_ecoupon_list( $data = array() )
	{

		$_this = new self();

		$result = array();

		$status_txt = $_this->status;

       	$ecoupon_type = $_this->get_ecoupon_type();

       	$ecoupon_match_type = $_this->get_ecoupon_match_type();
       	
       	$ecoupon_send_type = $_this->get_ecoupon_send_type();

       	$store_type = Option_logic::get_store_data();

		$data = Ecoupon::get_ecoupon_list( $data );

		foreach ($data as &$row) 
		{

			if ( is_object($row) ) 
			{

				$covert_array = get_object_vars($row);

				$row->ecoupon_content_txt = $_this->ecoupon_content_to_string( $covert_array );

				$row->ecoupon_match_id = "";

				if ( (int)$row->match_type === 3 && !empty($row->match_id) ) 
				{

					$row->ecoupon_match_id = $store_type[$row->match_id]["name"];
				
				}

				if ( (int)$row->match_type === 4 ) 
				{

					$row->ecoupon_match_id = $row->match_id ;
					
				}

				
				$row->ecoupon_type = isset($ecoupon_type[$row->type]) ? $ecoupon_type[$row->type] : "" ; 
				
				$row->ecoupon_match_type = isset($ecoupon_match_type[$row->match_type]) ? $ecoupon_match_type[$row->match_type] : "" ; 
				
				$row->ecoupon_send_type = isset($ecoupon_send_type[$row->send_type]) ? $ecoupon_send_type[$row->send_type] : "" ; 
				
				$row->status_txt = isset($status_txt[$row->status]) ? $status_txt[$row->status] : "" ; 
			
			}

		}

		$result = $data;

		return $result;	

	}


	// 取得單筆資料

	public static function get_single_ecoupon( $ecoupon_id )
	{

		$_this = new self;

		$result = array();

		if ( !empty($ecoupon_id) && is_int($ecoupon_id) ) 
		{

			$data = Ecoupon::get_single_ecoupon( $ecoupon_id );

			foreach ($data as $key => $value) 
			{
			
				$result[$key] = $value;

			}

			$type = intval($result["type"]);

			switch ($type) 
			{

				case 1:
				case 2:
					
					$result["ecoupon_content_type" . $type] = $result["ecoupon_content"];

					break;

				case 3:
					
					$result["ecoupon_content_type" . $type] = json_decode($result["ecoupon_content"], true);

					break;

			}

			$match_type = intval($result["match_type"]);

			switch ($match_type) 
			{

				case 3:
					
					$result["parents_store_type"] = $result["match_id"];

					break;


				case 4:

					$result["match_id"] = array( 0 => $result["match_id"] );
					
					break;

			}

			$result["active"] = $result["status"];

		}

		return $result;

	}


	// 取得輸入邏輯陣列

	public static function get_ecoupon_input_template_array()
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

       	$ecoupon_type = $_this->get_ecoupon_type();

       	$ecoupon_match_type = $_this->get_ecoupon_match_type();
       	
       	$ecoupon_send_type = $_this->get_ecoupon_send_type();

        $store_type = Option_logic::get_store_data();

        $store_type_array = $_this->get_ecoupon_store_type_array( $store_type );

        $htmlData = array(
                        "name" => array(
                            "type"          => 1, 
                            "title"         => $txt["ecoupon_name"],
                            "key"           => "name",
                            "value"         => isset($data["name"]) ? $data["name"] : "" ,
                            "display"       => true,
                            "desc"          => $txt["ecoupon_name_desc"],
                            "attrClass"     => "",
                            "hasPlugin"     => ""
                        ),
                        "ecoupon_description" => array(
                            "type"          => 1, 
                            "title"         => $txt["ecoupon_description"],
                            "key"           => "description",
                            "value"         => "",
                            "display"       => true,
                            "desc"          => $txt["ecoupon_description_desc"],
                            "attrClass"     => "",
                            "hasPlugin"     => ""
                        ),
                        "ecoupon_type" => array(
                            "type"          => 2, 
                            "title"         => $txt["ecoupon_type"],
                            "key"           => "type",
                            "value"         => "",
                            "data"          => $ecoupon_type,
                            "display"       => true,
                            "desc"          => $txt["ecoupon_type_desc"],
                            "EventFunc"     => "typeChange",
                            "attrClass"     => "",
                            "hasPlugin"     => ""
                        ),
                        "ecoupon_content_type1" => array(
                            "type"          => 1, 
                            "title"         => $txt["ecoupon_precent"],
                            "key"           => "ecoupon_content_type1",
                            "value"         => "",
                            "display"       => false,
                            "desc"          => $txt["ecoupon_type1_desc"],
                            "attrClass"     => "hide ecouponType ecouponType1",
                            "hasPlugin"     => ""
                        ),
                        "ecoupon_content_type2" => array(
                            "type"          => 1, 
                            "title"         => $txt["ecoupon_value"],
                            "key"           => "ecoupon_content_type2",
                            "value"         => "",
                            "display"       => false,
                            "desc"          => $txt["ecoupon_type2_desc"],
                            "attrClass"     => "hide ecouponType ecouponType2",
                            "hasPlugin"     => ""
                        ),
                        "ecoupon_rule" => array(
                            "type"          => 4, 
                            "title"         => $txt["ecoupon_content"],
                            "key"           => "ecoupon_rule",
                            "value"         => "",
                            "display"       => false,
                            "desc"          => $txt["ecoupon_type3_desc"],
                            "child"         => array(
                                                    array(
                                                            "key"           => "ecoupon_content_type3[A][]",
                                                            "childTitle"    => "A"
                                                    ),
                                                    array(
                                                            "key"           => "ecoupon_content_type3[B][]",
                                                            "childTitle"    => "B"                                                
                                                    )
                                                ),
                            "attrClass"     => "hide ecouponType ecouponType3",
                            "hasPlugin"     => "",
                            "data"     		=> array(
                            						array(
                            							"A" => "",
                            							"B" => ""
                            						)
                            					)
                        ),
                        "ecoupon_match_type" => array(
                            "type"          => 2, 
                            "title"         => $txt["ecoupon_match_type"],
                            "key"           => "match_type",
                            "value"         => "",
                            "data"          => $ecoupon_match_type,
                            "display"       => true,
                            "desc"          => $txt["ecoupon_match_type_desc"],
                            "attrClass"     => "",
                            "hasPlugin"     => ""
                        ),
                        "ecoupon_match_category" => array(
                            "type"          => 2, 
                            "title"         => $txt["category"],
                            "key"           => "parents_store_type",
                            "value"         => "",
                            "data"          => $store_type_array,
                            "display"       => false,
                            "desc"          => $txt["ecoupon_match_category_desc"],
                            "attrClass"     => "hide matchType matchType3",
                            "hasPlugin"     => ""
                        ),
                        "ecoupon_match_id" => array(
                            "type"          => 1, 
                            "title"         => $txt["ecoupon_match_id"],
                            "key"           => "match_id[]",
                            "value"         => "",
                            "data"          => "",
                            "display"       => false,
                            "desc"          => $txt["ecoupon_match_type4_desc"],
                            "attrClass"     => "hide matchType matchType4",
                            "hasPlugin"     => ""
                        ),
                        "ecoupon_send_type" => array(
                            "type"          => 2, 
                            "title"         => $txt["ecoupon_send_type"],
                            "key"           => "send_type",
                            "value"         => "",
                            "data"          => $ecoupon_send_type,
                            "display"       => true,
                            "desc"          => $txt["ecoupon_send_type_desc"],
                            "attrClass"     => "",
                            "hasPlugin"     => ""
                        ),
                        "max_num" => array(
                            "type"          => 1, 
                            "title"         => $txt["max_num"],
                            "key"           => "max_num",
                            "value"         => "",
                            "display"       => true,
                            "desc"          => $txt["max_num_desc"],
                            "attrClass"     => "",
                            "hasPlugin"     => ""
                        ),
                        "start_date" => array(
                            "type"          => 1, 
                            "title"         => $txt["start_date"],
                            "key"           => "start_date",
                            "value"         => "",
                            "display"       => true,
                            "desc"          => $txt["ecoupon_start_date_desc"],
                            "attrClass"     => "",
                            "hasPlugin"     => "DateTimePicker"
                        ),
                        "end_date" => array(
                            "type"          => 1, 
                            "title"         => $txt["end_date"],
                            "key"           => "end_date",
                            "value"         => "",
                            "display"       => true,
                            "desc"          => $txt["ecoupon_end_date_desc"],
                            "attrClass"     => "",
                            "hasPlugin"     => "DateTimePicker"
                        ),
                        "ecoupon_active_date" => array(
                            "type"          => 1, 
                            "title"         => $txt["ecoupon_active_date"],
                            "key"           => "ecoupon_active_date",
                            "value"         => "",
                            "display"       => true,
                            "desc"          => $txt["ecoupon_active_date_desc"],
                            "attrClass"     => "",
                            "hasPlugin"     => "DateTimePicker"
                        ),
                        "ecoupon_deadline" => array(
                            "type"          => 1, 
                            "title"         => $txt["ecoupon_deadline"],
                            "key"           => "deadline",
                            "value"         => "",
                            "display"       => true,
                            "desc"          => $txt["ecoupon_deadline_desc"],
                            "attrClass"     => "",
                            "hasPlugin"     => "DateTimePicker"
                        ),
                        "active" => array(
                            "type"          => 3, 
                            "title"         => "",
                            "key"           => "active",
                            "value"         => "",
                            "display"       => true,
                            "desc"          => "",
                            "attrClass"     => "",
                            "hasPlugin"     => ""
                        ),
                    );

		return $htmlData;

	}


	// 取得本模組使用的資料格式

	public static function get_ecoupon_store_type_array( $data = array() )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

	        foreach ($data as $key => $row) 
	        {

	            $result[$key] = $row["name"];

	        }			
			
		}

		return $result;

	}


	// 組合資料

	public static function ecoupon_input_data_bind( $htmlData, $OriData )
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

			if ( !empty($OriData["ecoupon_content_type3"]) && is_array($OriData["ecoupon_content_type3"]) ) 
			{

				foreach ($OriData["ecoupon_content_type3"]["A"] as $index => $value) 
				{
					
					$htmlData["ecoupon_rule"]["data"][$index]["A"] = $value;

				}

				foreach ($OriData["ecoupon_content_type3"]["B"] as $index => $value) 
				{
					
					$htmlData["ecoupon_rule"]["data"][$index]["B"] = $value;

				}
				
			}

			if ( !empty($OriData["match_id"]) && is_array($OriData["match_id"]) ) 
			{

				$htmlData["ecoupon_match_id"]["value"] = $OriData["match_id"][0] ;

			}

		}

		return $htmlData;

	}


	// 取得列表邏輯陣列

	public static function get_ecoupon_list_template_array()
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

        $htmlData = array(
                        "title" => array(
                        				$txt['id'],
                        				$txt['ecoupon_name'],
                        				$txt['ecoupon_type'],
                        				$txt['ecoupon_content'],
                        				$txt['ecoupon_match_type'],
                        				$txt['ecoupon_match_id'],
                        				$txt['ecoupon_send_type'],
                        				$txt['max_num'],
                        				$txt['start_date'],
                        				$txt['end_date'],
                        				$txt['ecoupon_active_date'],
                        				$txt['deadline'],
                        				$txt['status'],
                        				$txt['create_time'],
                        				$txt['action']
                        			),
                        "data" => array()
                    );

		return $htmlData;

	}


	// 組合列表資料

	public static function ecoupon_list_data_bind( $htmlData, $OriData )
	{

		$_this = new self();

		$result = $htmlData;

		if ( !empty($OriData) && $OriData->isNotEmpty() ) 
		{

			foreach ($OriData as $row) 
			{
	
				if ( is_object($row) ) 
				{

					$data = array(
								"data" => array(
												"id" 					=> $row->id,
												"ecoupon_name" 			=> $row->name,
												"ecoupon_type" 			=> $row->ecoupon_type,
												"ecoupon_content" 		=> $row->ecoupon_content_txt ,
												"ecoupon_match_type" 	=> $row->ecoupon_match_type,
												"ecoupon_match_id" 		=> !empty($row->ecoupon_match_id) && is_array($row->ecoupon_match_id) ? implode(",", $row->ecoupon_match_id) : $row->ecoupon_match_id ,
												"ecoupon_send_type" 	=> $row->ecoupon_send_type,
												"max_num" 				=> $row->max_num > 0 ? $row->max_num : "無上限" ,
												"start_date" 			=> $row->start_date,
												"end_date" 				=> $row->end_date,
												"ecoupon_active_date" 	=> $row->ecoupon_active_date,
												"deadline" 				=> $row->deadline,
												"status" 				=> $row->status_txt,
												"create_time" 			=> $row->created_at
											),
								"Editlink" => "/ecoupon/" . $row->id . "/edit?"
							);
					
				}

				$result["data"][] = $data;
			
			}


		}

		return $result;

	}


	// 發送ecoupon

	public static function send_ecoupon( $send_type, $user_id )
	{

		$_this = new self();

		$result = false;

		if ( !empty($send_type) && is_int($send_type) && !empty($user_id) && is_int($user_id) ) 
		{

			// 取得該User所有名下的store_id

			$store_info = Store_logic::get_store_info_logic( $user_id );

			// 撈取待發送的Ecoupon

			$active_ecoupon = $_this->get_active_ecoupon( $send_type );

			foreach ($active_ecoupon as $row) 
			{

				foreach ($store_info as $store_data) 
				{

					// 取得該券發送紀錄

					$ecoupon_user_list_data = $_this->get_ecoupon_user_list( (int)$ecoupon_id = $row["id"] );

					// 確認發送資格

					$can_send = $_this->check_ecoupon_qualifications( $ecoupon_data = $row, $ecoupon_user_list_data, $store_data );
				
					// 發送Ecoupon

					if ( $can_send === true ) 
					{

						$_this->add_ecoupon_user_list( (int)$ecoupon_id = $row["id"], (int)$store_id = $store_data->id );
					
						$result = true;

					}

				}

			}

			if ( $result === true ) 
			{

				// 寫入訊息

				$subject = "恭喜獲得折價券";

				$content = "請前往[GO商城] > [財產清單]中查看！";

				Msg_logic::add_normal_msg( $subject, $content, $user_id );
					
			}

		}

		return $result ;
	     
	}


	// 取出user可用的折價券

	public static function get_user_active_ecoupon( $store_id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($store_id) && is_int($store_id) ) 
		{		

			// 取出資料

			$data = Ecoupon::get_user_active_ecoupon( $store_id );

			foreach ($data as $row) 
			{

				$result[] = array(
								"name" => $row->name,
								"code" => $row->code
							);

			}

		}

		return $result ;

	}


	// 取出即將的折價券

	public static function get_expiring_ecoupon()
	{

		$_this = new self();

		$result = array();

		$date = date("Y-m-d H:i:s", mktime( date("H"), date("i"), date("s"), date("m"), date("d") + 7, date("Y") ) );

		// 取出資料

		$data = Ecoupon::get_expiring_ecoupon( $date );

		foreach ($data as $row) 
		{

			$result[] = array(
							"store_id" 	=> (int)$row->store_id,
							"name" 		=> $row->name,
							"code" 		=> $row->code
						);

		}

		return $result ;

	}


	// 寫入紀錄format

	public static function insert_record_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
				"record_id"     		=> isset($data["record_id"]) ? intval($data["record_id"]) : "",
				"ecoupon_use_id"       	=> isset($data["ecoupon_use_id"]) ? intval($data["ecoupon_use_id"]) : "",
				"store_id"       		=> isset($data["store_id"]) ? intval($data["store_id"]) : "",
				"discount"       		=> isset($data["discount"]) ? intval($data["discount"]) : "",
				"created_at"    		=> date("Y-m-d H:i:s"),
				"updated_at"    		=> date("Y-m-d H:i:s")
			);

		}

		return $result;

	}



	// 寫入ecoupon使用記錄

	public static function add_ecoupon_use_record( $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) ) 
		{

			$result = Ecoupon::add_ecoupon_use_record( $data );

			$result = true;

		}

		return $result ;
	     
	}


	// 將ecoupon標註為已使用

	public static function inactive_ecoupon_use_status( $code )
	{

		$result = false;

		if ( !empty($code) && is_string($code) ) 
		{

			$result = Ecoupon::inactive_ecoupon_use_status( $code );

			$result = true;

		}

		return $result ;
	     
	}


	// 驗證Ecoupon Code 正確性

	public static function test_ecoupon_code( $code )
	{

		$_this = new self();

		$result = array(
						"result" 	=> true,
						"msg" 		=> "",
						"data" 		=> array()
					);

		$Store = Session::get( 'Store' );

        try
        {

        	if ( empty($code) || !is_string($code) ) 
        	{
        		
        		throw new \Exception( "Ecoupon Code錯誤" );
        		
        	}

        	$data = $_this->ecoupon_decode( $code );

        	$full_data = $_this->get_ecoupon_full_data( $code );

        	if ( !isset($data["ecoupon_id"]) || !isset($data["store_id"]) || !isset($data["time"]) ) 
        	{
        		
        		throw new \Exception( "Ecoupon Code解析錯誤" );
        		
        	}

        	if ( $data["ecoupon_id"] !== $full_data["id"] ) 
        	{
        		
        		throw new \Exception( "Ecoupon Data錯誤" );
        		
        	}

        	if ( $data["store_id"] !== $Store ) 
        	{
        		
        		throw new \Exception( "Ecoupon Owner錯誤" );
        		
        	}

        	if ( strtotime($full_data["ecoupon_active_date"]) > time() ) 
        	{
        		
        		throw new \Exception( "Ecoupon 使用期限尚未開始" );
        		
        	}

        	if ( strtotime($full_data["deadline"]) < time() ) 
        	{
        		
        		throw new \Exception( "Ecoupon 已過期" );
        		
        	}

			$result = array(
							"result" 	=> true,
							"msg" 		=> "",
							"data" 		=> $full_data
						);

        }
        catch(\Exception $e)
        {

			$result = array(
							"result" 	=> false,
							"msg" 		=> $e->getMessage(),
							"data" 		=> array()
						);

        }

		return $result;

	}


	// 取得該券完整資料

	public static function get_ecoupon_full_data( $code )
	{

		$_this = new self();

		$result = array();

		if ( !empty($code) && is_string($code) ) 
		{

			$data = Ecoupon::get_ecoupon_full_data( $code );
			
			foreach ($data as $row) 
			{

				foreach ($row as $key => $value) 
				{
					
					$result[$key] = $value;

				}

			}

		}


		return $result;

	}


	// 計算折價金額

	public static function get_ecoupon_discount_price( $type, $ecoupon_content, $shop_car_total )
	{

		$result = array(
						"result" 			=> false,
						"discount_price" 	=> 0,
						"msg" 				=> "default"
					);


		if ( !empty($type) && is_int($type) && !empty($ecoupon_content) && !empty($shop_car_total) && is_int($shop_car_total) ) 
		{

	        try
	        {

	        	switch ($type) 
	        	{

	        		case 1:
	        			
	        			if ( $ecoupon_content > 1 ) 
	        			{

	        				throw new \Exception( "折扣數錯誤" );
		        				
	        			}

	        			$tmp_price = ceil($shop_car_total * $ecoupon_content);

	        			$discount_price = $tmp_price - $shop_car_total;

	        			break;

	        		case 2:
	        			
	        			$discount_price = $ecoupon_content * -1;

	        			// if ( $shop_car_total + $discount_price < 0 ) 
	        			// {

	        			// 	throw new \Exception( "金額為負數" );
		        				
	        			// }

	        			break;
	        		
	        		case 3:

	        			$data = json_decode( $ecoupon_content, true );

	        			$sub_discount = 0;

	        			foreach ($data["A"] as $key => $value) 
	        			{

	        				if ( $value <= $shop_car_total ) 
	        				{

	        					$sub_discount += $data["B"][$key];
	        					
	        				}

	        			}

	        			if ( $sub_discount === 0 ) 
	        			{

	        				throw new \Exception( "不符合使用級距，最低結帳金額: " . $data["A"][0] . "，本次結帳金額: " . $shop_car_total );
	        				
	        			}

	        			$discount_price = $sub_discount * -1;

	        			break;

	        		default:
	        			
	        			throw new \Exception( "Ecoupon 類型錯誤" );

	        			break;
	        	}


				$result = array(
								"result" 			=> true,
								"discount_price" 	=> intval($discount_price)
							);

	        }
	        catch(\Exception $e)
	        {

				$result = array(
								"result" 			=> false,
								"msg" 				=> $e->getMessage(),
								"discount_price" 	=> 0
							);

	        }
			
		}

		return $result;

	}


	// 取得使用記錄

	public static function get_ecoupon_use_record( $store_id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($store_id) && is_int($store_id) ) 
		{

			$data = Ecoupon::get_ecoupon_use_record( $store_id );
			
			foreach ($data as $row) 
			{

				$result[$row->record_id] = $row->discount;

			}

		}


		return $result;

	}


	// 取出user可用的折價券資料

	public static function get_user_active_ecoupon_data( $store_id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($store_id) && is_int($store_id) ) 
		{		

			// 取出資料

			$data = Ecoupon::get_user_ecoupon_data( $store_id );

			foreach ($data as $row) 
			{

				$result[] = array(
								"name" 				=> $row->name,
								"type" 				=> $row->type,
								"ecoupon_content" 	=> $row->ecoupon_content,
								"status" 			=> $row->status,
								"created_at" 		=> $row->created_at,
								"deadline" 			=> $row->deadline
							);

			}

		}

		return $result ;

	}


	public static function ecoupon_content_to_string( $data )
	{

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			switch ($data["type"]) 
			{

				case 1:

					$result[] = $data["ecoupon_content"] . "折";

					break;

				case 2:

					$result[] = "折抵" . $data["ecoupon_content"] . "元";

					break;

				case 3:

					$tmp_ecoupon_content = isset($data["ecoupon_content"]) ? json_decode($data["ecoupon_content"], true) : "";

					$sub_total = 0;

					foreach ($tmp_ecoupon_content["A"] as $index => $value) 
					{

					  $sub_total += $tmp_ecoupon_content["B"][$index];

					  $result[] = "滿" . $value . "元，折" . $sub_total . "元";

					}

					break;
			}
			
		}

		return $result;

	}


	// 取得系統所有生效中的Ecoupon

	protected function get_active_ecoupon( $send_type )
	{

		$result = array();

		if ( !empty($send_type) && is_int($send_type) )
		{

			$data = Ecoupon::get_active_ecoupon( $send_type );

			foreach ($data as $row) 
			{

				$result[] = array(
								"id" 			=> (int)$row->id,
								"name" 			=> $row->name,
								"type" 			=> (int)$row->type,
								"max_num" 		=> (int)$row->max_num,
								"match_type"	=> (int)$row->match_type,
								"match_id"		=> (int)$row->match_id,
								"send_type" 	=> (int)$row->send_type,
								"ecoupon_active_date" 	=> $row->ecoupon_active_date,
								"deadline" 		=> $row->deadline,
							);

			}

		}

		return $result ;

	}


	// 取得發送紀錄

	protected function get_ecoupon_user_list( $ecoupon_id )
	{

		$result = array();

		if ( !empty($ecoupon_id) && is_int($ecoupon_id) )
		{

			$data = Ecoupon::get_ecoupon_user_list( $ecoupon_id );

			foreach ($data as $row) 
			{

				$result[] = $row->store_id;

			}

		}

		return $result ;

	}


	// 確認資格

	protected function check_ecoupon_qualifications( $ecoupon_data, $ecoupon_user_list_data, $store_data )
	{

		$result = false;

		if ( !empty($ecoupon_data) && is_array($ecoupon_data) && is_array($ecoupon_user_list_data) && is_object($store_data) )
		{

			// 判斷資格

			switch ($ecoupon_data["match_type"]) 
			{

				// 全部用戶

				case 1:
					
					$result = true;

					break;


				// 新用戶
				
				case 2:

					$max = 60 * 60 * 24 * 30;

					$result = time() - strtotime($store_data->created_at) <= $max ? true : false ;

					break;


				// 特定類別

				case 3:

					$store_type_id = (int)$store_data->store_type;

					$store_type_data = Option_logic::get_store_data();

					$store_parent_type = Store_logic::get_store_parent_type( $store_type_id, $store_type_data );

					$result = $ecoupon_data["match_id"] === $store_parent_type ? true : false ;

					break;

				// 特定用戶

				case 4:

					$result = $ecoupon_data["match_id"] === (int)$store_data->id ? true : false ;

					break;					

			}

			// 確認有無領取紀錄

			$result = in_array($store_data->id, $ecoupon_user_list_data) ? false : $result ;

			// 確認總發送數有無超出上限，0表示無上限

			$result = count($ecoupon_user_list_data) >= $ecoupon_data["max_num"] && $ecoupon_data["max_num"] > 0 ? false : $result ;

		}

		return $result ;

	}


	// 寫入發送紀錄

	protected function add_ecoupon_user_list( $ecoupon_id, $store_id )
	{

		$_this = new self();

		$result = false;

		if ( !empty($ecoupon_id) && is_int($ecoupon_id) && !empty($store_id) && is_int($store_id) )
		{

			$data = array(
						"ecoupon_id" 	=> $ecoupon_id,
						"store_id" 		=> $store_id,
						"code" 			=> $_this->get_ecoupon_code( $ecoupon_id, $store_id ),
						"status" 		=> 1,
						"created_at" 	=> date("Y-m-d H:i:s"),
						"updated_at" 	=> date("Y-m-d H:i:s")
					);

			Ecoupon::add_ecoupon_user_list( $data );

			$result = true;

		}

		return $result ;

	}


	// 取得亂數code

	protected function get_ecoupon_code( $ecoupon_id, $store_id )
	{

		$_this = new self();

		$result = "";

		if ( !empty($ecoupon_id) && is_int($ecoupon_id) && !empty($store_id) && is_int($store_id) )
		{

			$data = array(
						"ecoupon_id" 	=> $ecoupon_id,
						"store_id" 		=> $store_id,
						"time" 			=> time()
					);

			$json = json_encode($data);

			$result = $_this->rand(3) . base64_encode($json) . $_this->rand(3);

			$_this->ecoupon_decode( $result );

		}

		return $result ;

	}


	// 亂數code 解碼

	protected function ecoupon_decode( $code )
	{

		$_this = new self();

		$result = array();

		if ( !empty($code) && is_string($code) )
		{

			$tmp = substr( $code, 3, mb_strlen($code) - 6 );

			$tmp = base64_decode($tmp);

			$result = json_decode($tmp, true);

		}

		return $result ;

	}

}
