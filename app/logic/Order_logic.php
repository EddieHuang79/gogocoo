<?php

namespace App\logic;

use App\model\Order;
use App\logic\Option_logic;
use App\logic\Stock_logic;
use App\logic\Msg_logic;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class Order_logic extends Basetool
{

	protected $status_txt = array(
								1	=>	"未入帳",
								2	=>	"已入帳"
							);

	protected $out_warehouse_category;

	protected $warehouse_txt = array(
									1 => "自家倉庫"
								);

	protected $logistics_type_txt = array(
									1 => "是",
									2 => "否"
								);

	protected $not_show_on_page = array(
									'pay_status', 
									'pay_time', 
									'refund_time', 
									'cancel_time',
									'ori_order_number',      // 訂單編號
									'ori_order_date',        // 訂單日期
									'ori_order_status',      // 訂單狀態
									'ori_custom_name',       // 顧客姓名
									'ori_custom_email',      // email
									'ori_custom_phone',      // 顧客電話
									'ori_custom_sex',        // 性別
									'ori_custom_birthday',   // 生日
									'remark',                // 備註
									'send_type',             // 送貨方式
									'send_status',           // 送貨狀態
									'pay_type',              // 付款方式
									'pay_status',            // 付款狀態
									'sub_total',             // 小計
									'post_fee',              // 運費
									'attr_fee',              // 附加費
									'discount_fee',          // 優惠
									'total',                 // 合計
									'trade_code',            // 交易編號
									'pay_date',               // 付款日期
									'currency',               // 幣別
									'admin_remark',           // 管理員備註
									'receiver',               // 收件人
									'receiver_phone',         // 收件人電話
									'receiver_address1',      // 收件人地址1
									'receiver_address2',      // 收件人地址2
									'receiver_city',          // 收件人城市
									'receiver_section',       // 收件人區域
									'receiver_country',       // 收件人國家
									'post_code',              // 收件人郵遞區號
									'send_remark',            // 送貨備註
									'receiver_store_name',    // 收件門市
									'receiver_store_code',    // 收件店號
									'send_code',              // 送貨編號
									'receipt_number',         // 發票號碼
									'receipt_title',          // 發票抬頭
									'company_code',           // 公司統編
									'receipt_address',        // 發票地址
									'product_code',           // 產品編號
									'product_name',           // 產品名稱
									'option',                 // 選項
									'is_additional_product',  // 是否為加購品
									'is_preorder',            // 是否為預購
									'preorder_hint'           // 預購提示
							);

	public function __construct()
	{

		// 文字
		$this->out_warehouse_category = Option_logic::get_out_warehouse_category();

	}


	// 取得額外欄位

	public static function get_order_extra_column()
	{

		$_this = new self();

		$result = array();

		$data = Order::get_order_extra_column();

		$not_show_on_page = $_this->not_show_on_page;

		unset($data[0], $data[1]);

		if ( !empty($data) ) 
		{

			foreach ($data as $key => $row) 
			{

				$result[$key]["name"] = $row->Field;

				$result[$key]["show_on_page"] = true;

				// 基本案例

				if ( strpos($row->Type, "varchar") !== false || strpos($row->Type, "date") !== false ) 
				{
					$result[$key]["type"] = "text";
				}

				if ( strpos($row->Type, "int") !== false ) 
				{
					$result[$key]["type"] = "number";
				}

				if ( strpos($row->Type, "text") !== false ) 
				{
					$result[$key]["type"] = "textarea";
				}

				// 特例

				if ( $row->Field == 'logistics_type' ) 
				{
					$result[$key]["type"] = "radio";
				}

				if ( in_array($row->Field, $not_show_on_page) ) 
				{
					$result[$key]["show_on_page"] = false;
				}

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

			$shop_id = Session::get( 'Store' );

			$spec_id = isset($data["spec_id"]) ? intval($data["spec_id"]) : 0 ;

			$product_id = isset($data["product_id"]) ? intval($data["product_id"]) : 0 ;

	        if ( !empty($product_id) ) 
	        {

				$order_number = $_this->get_order_number();

				$number = isset($data["number"]) ? intval($data["number"]) : 0 ;

				$result = array(
				            "shop_id"      			=> isset($shop_id) ? $shop_id : 1,
				            "product_id"			=> $product_id,
				            "spec_id"				=> $spec_id,
				            "order_number"			=> $order_number,
				            "number"       			=> $number,
				            "out_warehouse_date" 	=> isset($data["out_warehouse_date"]) && !empty($data["out_warehouse_date"]) ? date("Y-m-d", strtotime($data["out_warehouse_date"])) : date("Y-m-d"),
				            "category"    			=> isset($data["category"]) ? intval($data["category"]) : 1,
				            "status"    			=> 1,
				            "created_at"    		=> date("Y-m-d H:i:s"),
				            "updated_at"    		=> date("Y-m-d H:i:s")
				         );

	        }

        }

		return $result;

	}


	// 新增附屬欄位格式

	public static function insert_extra_format( $data, $order_extra_column, $order_id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) && !empty($order_extra_column) && is_array($order_extra_column) && !empty($order_id) && is_int($order_id) ) 
		{

			$result["order_id"] = $order_id;

			foreach ($order_extra_column as $row_data) 
			{

				$default_value = $row_data['type'] === 'number' ? 0 : "" ;

				$result[$row_data['name']] = isset($data[$row_data['name']]) && !empty($data[$row_data['name']]) ? trim($data[$row_data['name']]) : $default_value ;

			}

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

			$spec_id = isset($data["spec_id"]) ? intval($data["spec_id"]) : 0 ;

			$product_id = isset($data["product_id"]) ? intval($data["product_id"]) : 0 ;

			$result = array(
			            "product_id"      		=> $product_id,
			            "spec_id"      			=> $spec_id,
			            "number"       			=> isset($data["number"]) ? intval($data["number"]) : 0,
			            "out_warehouse_date" 	=> isset($data["out_warehouse_date"]) && !empty($data["out_warehouse_date"]) ? date("Y-m-d", strtotime($data["out_warehouse_date"])) : date("Y-m-d"),
			            "category"    			=> isset($data["category"]) ? intval($data["category"]) : 1,
			            "updated_at"    		=> date("Y-m-d H:i:s")
			        );

		}

		return $result;

	}


	// 附屬資料更新格式

	public static function update_extra_format( $data, $purchase_extra_column )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) && !empty($purchase_extra_column) && is_array($purchase_extra_column) ) 
		{

			foreach ($purchase_extra_column as $row_data) 
			{

				$default_value = $row_data['type'] == 'number' ? 0 : "" ;

				$result[$row_data['name']] = isset($data[$row_data['name']]) && !empty($data[$row_data['name']]) ? trim($data[$row_data['name']]) : $default_value ;

			}

		}

		return $result;

	}


	// 新增訂單

	public static function add_order( $data )
	{

		$result = !empty($data) && is_array($data) ? Order::add_order( $data ) : false;

		return $result;

	}


	// 新增額外訂單

	public static function add_extra_order( $data )
	{

		$result = !empty($data) && is_array($data) ? Order::add_extra_order( $data ) : false;

		return $result;

	}


	// 修改訂單

	public static function edit_order( $data, $order_id )
	{

		$result = !empty($data) && is_array($data) ? Order::edit_order( $data, $order_id ) : false;

		return $result;

	}


	// 修改訂單額外欄位

	public static function edit_order_extra_data( $data, $order_id )
	{

		$result = !empty($data) && is_array($data) ? Order::edit_order_extra_data( $data, $order_id ) : false;

		return $result;

	}


	// 取得訂單編號

	public static function get_order_number( $order_id = 0 )
	{

		$data = Order::get_order_number( $order_id );

		$result = !empty($data) ? $data->order_number + 1 : 1 ;

		return $result;

	}


	// 取得訂單資料

	public static function get_order_data( $data = array() )
	{

		$shop_id = Session::get( 'Store' );

		return Order::get_order_data( $data, $shop_id );

	}


	// 取得訂單列表

	public static function get_order_list( $data, $option_data )
	{

		$_this = new self();

		$status_txt = $_this->status_txt;

		$out_warehouse_category_txt = $_this->out_warehouse_category;

		$logistics_type_txt = $_this->logistics_type_txt;

		$result = array();

		if ( !empty($data) ) 
		{

			foreach ($data as $key1 => $row) 
			{
				
				foreach ($row as $key2 => $data) 
				{

					$result[$key1][$key2] = $data;

				}

				$category = $row->category;

				$result[$key1]['out_warehouse_category_txt'] = $out_warehouse_category_txt->$category;

				$result[$key1]['status_txt'] = $status_txt[$row->status];

				$result[$key1]['order_number_txt'] = $_this->order_number_encode($row);

				$result[$key1]['logistics_type_txt'] = isset($logistics_type_txt[$row->logistics_type]) ? $logistics_type_txt[$row->logistics_type] : $logistics_type_txt[2] ;

				foreach ($option_data as $key2 => $data) 
				{

					$result[$key1][$key2.'_txt'] = isset( $data[$row->$key2] ) ? $data[$row->$key2] : "" ;
				
				}

			}

		}

		return $result;

	}


	// 取得單筆訂單

	public static function get_single_order_data( $id )
	{

		$result = array();

		if ( !empty($id) && is_int($id) )
		{

			$result = Order::get_single_order_data( $id );

		}

		return $result;

	}


	// FIFO Controller

	public static function FIFO_get_stock_id( $data )
	{

		$_this = new self();

		$result = array();

		$product_id = array();
		
		$spec_id = array();

		if ( !empty($data) ) 
		{

			foreach ($data as $row) 
			{

				if ( is_object($row) ) 
				{

					$product_id[] = $row->product_id;

					$spec_id[] = $row->spec_id;

				}

			}

			$product_id = array_filter($product_id, "intval");

			$spec_id = array_filter($spec_id, "intval");

			$stock_data = Stock_logic::FIFO_get_stock_id( $product_id, $spec_id );

	    	$stock_data = $_this->change_format($stock_data);

	    	// 爛招 丟session

	    	Session::forget('stock_data');

	    	Session::put('stock_data', $stock_data);

			foreach ($data as $row) 
			{

				if ( is_object($row) ) 
				{

					$stock_key = $_this->made_key(array($row->product_id, $row->spec_id));

					$result[$row->id] = $_this->FIFO_stock( $stock_key, $row->number );

				}

			}

		}

		return $result;

	}


	// 轉換格式

	public static function change_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) ) 
		{

			foreach ($data as $row) 
			{

				if ( is_object($row) ) 
				{

					$stock_key =  $_this->made_key( array( $row->product_id, $row->spec_id ) );

					$result[$stock_key][$row->in_warehouse_number] = array(
																			$row->id => $row->stock
																		);

				}

			}

		}

		return $result;

	}


	// FIFO core

	public static function FIFO_stock( $stock_key, $request_number )
	{

		$result = array();

		$stock_result = array();

		$data = Session::get('stock_data');

		if ( !empty($data) ) 
		{

			$stock_data = $data;

			if ( isset($data[$stock_key]) && !empty($data[$stock_key]) && !empty($request_number) && is_int($request_number) ) 
			{

				foreach ($data[$stock_key] as &$row) 
				{

					foreach ($row as $stock_id => &$stock) 
					{

						// 判斷結果

						$tmp = $stock - $request_number;
						
						if ( $tmp >= 0 ) 
						{
							$stock_result[] = array(
													$stock_id => $request_number
												);

							$request_number = 0;

							// 更新陣列數

							$stock = $tmp > 0 ? $tmp : 0 ;

							break 2;
						}
						else
						{

							$request_number = abs($tmp) ; 

							$stock_result[] = array(
													$stock_id => $stock
												);

							// 更新陣列數

							$stock = $tmp > 0 ? $tmp : 0 ;

						}

					}

				}

			}

			// 分配成功，更新陣列; 分配失敗，還原陣列

			$request_number > 0 ? Session::put('stock_data', $stock_data) : Session::put('stock_data', $data) ;

			$result = array(
							
							"result" 	=> $request_number > 0 ? false : true,

							"data" 		=> $request_number > 0 ? array() : $stock_result
						
						);

		}

		return $result;

	}


	// 多筆訂單核可

	public static function get_order_data_for_verify( $id )
	{

		$result = array();

		if ( !empty($id) && is_array($id) && count($id) > 0 ) 
		{

			$result = Order::get_order_data_for_verify( $id );

		}

		return $result;

	}


	// 改變訂單狀態

	public static function change_status( $id )
	{

		$result = !empty($id) && is_int($id) ? Order::change_status( $id ) : false;

		return $result;

	}


	// 取得訂單紀錄格式

	public static function order_stock_record_format( $data, $order_id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) && !empty($order_id) && is_int($order_id) ) 
		{

			foreach ($data as $row) 
			{

				foreach ($row as $stock_id => $cost_stock) 
				{

					$result[] = array(
										"order_id" 		=> $order_id,
										"stock_id" 		=> $stock_id,
										"cost_number" 	=> $cost_stock,
										"created_at" 	=> date("Y-m-d H:i:s"),
										"updated_at" 	=> date("Y-m-d H:i:s"),
									);
				}

			}

		}

		return $result;

	}


	// 新增訂單紀錄

	public static function add_order_stock_record( $data )
	{

		$result = !empty($data) && is_array($data) ? Order::add_order_stock_record( $data ) : false;

		return $result;

	}


	// 訂單編號 編碼

	public static function order_number_encode( $order_data )
	{

		$result = is_object($order_data) ? date("Ymd", strtotime($order_data->created_at)) . str_pad($order_data->order_number, 3, "0", STR_PAD_LEFT) : false ;

		return $result;

	}


	// 訂單編號 解碼

	public static function order_number_decode( $order_number )
	{

		$result = !empty($order_number) ? substr($order_number, 8) : false ;

		$result = !empty($result) ? intval($result) : false ;

		return $result;

	}


	// 訂單核可 controller

	public static function order_verify( $order_id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($order_id) && is_array($order_id) ) 
		{

	        // filter

	        $order_id_array = array_filter($order_id, "intval");

	        // 取得訂單資料 預期回傳 id,product_id,spec_id,number

	        $order_data = $_this->get_order_data_for_verify( $order_id_array ); 

	        $FIFO_result = $_this->FIFO_get_stock_id( $order_data ); 

	        $Login_user = Session::get("Login_user");

	        $error_data = array();

	        foreach ($FIFO_result as $order_id => $data) 
	        {

	        	if ( isset($data['result']) && $data['result'] === true ) 
	        	{

	    			// 更改狀態

	    			$_this->change_status( $order_id );

	    			// 紀錄扣庫

	    			$order_stock_record = $_this->order_stock_record_format( $data['data'], $order_id );

	    			$_this->add_order_stock_record( $order_stock_record );

	    			// 扣除庫存

	    			Stock_logic::cost_stock( $order_stock_record );

	        	}
	        	else
	        	{

	        		$fail_data = $_this->get_single_order_data($order_id);

	        		$fail_data = $fail_data[0];

	        		$order_number = $_this->order_number_encode($fail_data);

	        		$error_data[] = $order_number;

	        	}

	        }

			$subject = !empty($error_data) ? "庫存不足" : "入帳成功" ;

			$content = !empty($error_data) ? "庫存不足，無法入帳！訂單編號: ". implode(",", $error_data) : $subject ;

	        Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"], $show_type = 2 );

		}

		return $result;

	}


	// 訂單上傳範例

	public static function order_upload_sample_output( $column_header )
	{

		$_this = new self();

		$result = array();

		$column = array();

		$shop_id = Session::get( 'Store' );	

		if ( !empty($column_header) && is_array($column_header) ) 
		{

			foreach ($column_header as $row) 
			{

				$column[] = $row['name'];

			}

		}

		$data = Order::order_upload_sample_output( $shop_id );

		if ( !empty($data) ) 
		{

			foreach ($data as $key => $value) 
			{

				if ( in_array($key, $column) ) 
				{

					$result[$key] = $value;

				}

			}

		}

		return $result;

	}


	// 取得訂單數量

	public static function get_order_cnt( $week_date, $shop_id, $status )
	{

		$result = array();

		if ( !empty($week_date) && !empty($shop_id) && is_int($shop_id) && !empty($status) && is_array($status) ) 
		{

			$result = Order::get_order_cnt( $week_date, $shop_id, $status );

		}

		return $result;

	}


	// 取得訂單總和

	public static function get_order_sum( $week_date, $shop_id, $status )
	{

		$result = array();

		if ( !empty($week_date) && !empty($shop_id) && is_int($shop_id) && !empty($status) && is_array($status) ) 
		{

			$result = Order::get_order_sum( $week_date, $shop_id, $status );

		}

		return $result;

	}


	// 取得熱銷top5

	public static function get_hotSell_top5( $week_date, $shop_id, $status )
	{

		$result = array();

		if ( !empty($week_date) && !empty($shop_id) && is_int($shop_id) && !empty($status) && is_array($status) ) 
		{

			$result = Order::get_hotSell_top5( $week_date, $shop_id, $status );

		}

		return $result;

	}


	// 訂單匯出

	public static function order_output( $id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($id) && is_array($id) ) 
		{
		
			$data = Order::get_order_data_for_output( $id );

			foreach ($data as $row) 
			{

				$result[] = $_this->order_output_format( $row );
			
			}

		}

		return $result;

	}


	// Shopline 匯入格式

	public static function shopline_upload_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			// $shop_id = Session::get( 'Store' );

			// $spec_id = isset($data["spec_id"]) ? intval($data["spec_id"]) : 0 ;

			// $product_id = isset($data["product_id"]) ? intval($data["product_id"]) : 0 ;

	  //       if ( !empty($product_id) ) 
	  //       {

			// 	$order_number = $_this->get_order_number();

			// 	$number = isset($data["number"]) ? intval($data["number"]) : 0 ;

				$result = array(
				            "order_number"      	=> "",
				            "order_date"      		=> "",
				            "order_status"      	=> "",
				            "custom"      			=> "",
				            "email"      			=> "",
				            "phone_number"      	=> "",
				            "sex"      				=> "",
				            "birthday"      		=> "",
				            "remark"      			=> "",
				            "send_type"      		=> "",
				            "send_status"      		=> "",
				            "pay_type"      		=> "",
				            "pay_status"      		=> "",
				            "sub_total"      		=> "",
				            "post_fee"      		=> "",
				            "attr_fee"      		=> "",
				            "discount"      		=> "",
				            "total"      			=> "",
				            "trade_code"      		=> "",
				            "pay_date"      		=> "",
				            "currency"      		=> "",
				            "admin_remark"      	=> "",
				            "receiver"      		=> "",
				            "receiver_phone"      	=> "",
				            "receiver_address1"     => "",
				            "receiver_address2"     => "",
				            "receiver_city"     	=> "",
				            "receiver_section"     	=> "",
				            "receiver_country"     	=> "",
				            "post_code"     		=> "",
				            "send_remark"     		=> "",
				            "receiver_store_name"   => "",
				            "receiver_store_code"   => "",
				            "send_code"   			=> "",
				            "receipt_number"   		=> "",
				            "receipt_title"   		=> "",
				            "company_code"   		=> "",
				            "receipt_address"   	=> "",
				            "product_code"		   	=> "",
				            "option"		   		=> "",
				            "number"		   		=> "",
				            "attr_product"		   	=> "",
				            "is_preorder"		   	=> "",
				            "preorder_hint"		   	=> "",
				            "created_at"    		=> date("Y-m-d H:i:s"),
				            "updated_at"    		=> date("Y-m-d H:i:s")
				         );

	        // }

        }

		return $result;

	}


	// 取得不顯示在畫面的欄位

	public static function get_not_show_on_page()
	{

		$_this = new self();

		return $_this->not_show_on_page;

	}


	// 組合列表資料

	public static function order_list_data_bind( $OriData )
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$result = array(
                        "title" => array(
                        				array(
											"clickAll" 	=> true,
											"target" 	=> "order_checkbox"
										),
                        				$txt['order_number'],
                        				$txt['product_name'],
                        				$txt['out_warehouse_date'],
                        				$txt['out_warehouse_category']
                        			),
                        "data" => array()
                    );

		$order_extra_column_array = array();

		$order_extra_column = $_this->get_order_extra_column(); 

		foreach ($order_extra_column as $row) 
		{

			if ( $row["show_on_page"] === true ) 
			{
				
				$order_extra_column_array[] = $row["name"];
				
			}

		}



		foreach ($order_extra_column as $row) 
		{

			if ( isset($txt[$row['name']]) && $row["show_on_page"] === true ) 
			{
				
				$result["title"][] = $txt[$row['name']];
				
			}

		}


		$result["title"][] = $txt['number'];

		$result["title"][] = $txt['status'];

		$result["title"][] = $txt['action'];

		if ( !empty($OriData) && is_array($OriData) ) 
		{

			foreach ($OriData as $row) 
			{
	
				if ( is_array($row) ) 
				{

					$data = array(
								"data" => array(
						                        "id"          				=> array(
						                        										"id" 		=> $row["id"],
						                        										"checkbox" 	=> true,
						                        										"key" 		=> "order_id[]",
						                        										"class" 	=> "order_checkbox",
						                        								),									
												"order_number" 					=> $row["order_number_txt"],
												"product_name" 					=> $row["product_name"],
												"out_warehouse_date" 			=> $row["out_warehouse_date"],
												"out_warehouse_category" 		=> $row["out_warehouse_category_txt"]
											),
								"Editlink" => $row['status'] == 1 ? "/order/" . $row["id"] . "/edit?" : ""
							);

					foreach ($order_extra_column_array as $key) 
					{
						
						$data["data"][$key] = isset( $row[$key."_txt"] ) ? $row[$key."_txt"] : $row[$key] ;

					}

					$data["data"]["number"] = $row["number"];

					$data["data"]["status_txt"] = $row["status_txt"];
					
				}

				$result["data"][] = $data;
			
			}

		}

		return $result;

	}


	// 組合列表資料

	public static function order_verify_list_data_bind( $OriData )
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$result = array(
                        "title" => array(
                        				array(
											"clickAll" 	=> true,
											"target" 	=> "order_checkbox"
										),
                        				$txt['order_number'],
                        				$txt['product_name'],
                        				$txt['out_warehouse_date'],
                        				$txt['out_warehouse_category']
                        			),
                        "data" => array()
                    );

		$order_extra_column_array = array();

		$order_extra_column = $_this->get_order_extra_column(); 

		foreach ($order_extra_column as $row) 
		{

			if ( $row["show_on_page"] === true ) 
			{
				
				$order_extra_column_array[] = $row["name"];
				
			}

		}

		foreach ($order_extra_column as $row) 
		{

			if ( isset($txt[$row['name']]) && $row["show_on_page"] === true ) 
			{
				
				$result["title"][] = $txt[$row['name']];
				
			}

		}

		$result["title"][] = $txt['number'];

		$result["title"][] = $txt['status'];

		$result["title"][] = $txt['action'];

		if ( !empty($OriData) && is_array($OriData) ) 
		{

			foreach ($OriData as $row) 
			{
	
				if ( is_array($row) ) 
				{

					$data = array(
								"data" => array(
						                        "id"          				=> array(
						                        										"id" 		=> $row["id"],
						                        										"checkbox" 	=> true,
						                        										"key" 		=> "order_id[]",
						                        										"class" 	=> "order_checkbox",
						                        								),									
												"order_number" 					=> $row["order_number_txt"],
												"product_name" 					=> $row["product_name"],
												"out_warehouse_date" 			=> $row["out_warehouse_date"],
												"out_warehouse_category" 		=> $row["out_warehouse_category_txt"]
											),
								"Editlink" => $row['status'] == 1 ? "/order/" . $row["id"] . "/edit?" : ""
							);

					foreach ($order_extra_column_array as $key) 
					{
						
						$data["data"][$key] = isset( $row[$key."_txt"] ) ? $row[$key."_txt"] : $row[$key] ;

					}

					$data["data"]["number"] = $row["number"];

					$data["data"]["status_txt"] = $row["status_txt"];
					
				}

				$result["data"][] = $data;
			
			}

		}

		return $result;

	}


	// 取得輸入邏輯陣列

	public static function get_order_input_template_array()
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

        $out_warehouse_category_data = Option_logic::get_out_warehouse_category();

        $out_warehouse_category_data = get_object_vars($out_warehouse_category_data) ;

        $order_extra_column = Order_logic::get_order_extra_column();

        $select_option = Option_logic::get_select_option( $order_extra_column );

		$htmlData = array(
					"product_id" => array(
						"type"          => 11, 
						"title"         => "",
						"key"           => "product_id",
						"value"         => "" ,
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "hide",
						"hasPlugin"     => "",
						"placeholder"   => ""
					),
					"product_name" => array(
						"type"          => 10, 
						"title"         => $txt["product_name"],
						"key"           => "product_name",
						"value"         => "" ,
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => $txt['product_name_input']
					),
					"number" => array(
						"type"          => 1, 
						"title"         => $txt["number"],
						"key"           => "number",
						"value"         => "",
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => $txt['number_input']
					),
					"out_warehouse_date" => array(
						"type"          => 1, 
						"title"         => $txt["out_warehouse_date"],
						"key"           => "out_warehouse_date",
						"value"         => "",
						"display"       => true,
						"desc"          => "",
						"EventFunc"     => "",
						"attrClass"     => "",
						"hasPlugin"     => "DatePicker",
						"placeholder"   => $txt["out_warehouse_date_input"]
					),
					"out_warehouse_category" => array(
						"type"          => 2, 
						"title"         => $txt["out_warehouse_category"],
						"key"           => "out_warehouse_category",
						"value"         => "",
						"data"          => $out_warehouse_category_data,
						"display"       => true,
						"desc"          => "",
						"EventFunc"     => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => ""
					),
					"logistics_type" => array(
						"type"          => 3, 
						"title"         => $txt["logistics_type"],
						"key"           => "logistics_type",
						"value"         => "",
						"data"         	=> array(
												1 => $txt["yes"],
												2 => $txt["no"]
											),
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => ""
					),
					"ori_product_name" => array(
						"type"          => 1, 
						"title"         => $txt["ori_product_name"],
						"key"           => "ori_product_name",
						"value"         => "",
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => $txt["ori_product_name_input"]
					)
		         );

		return $htmlData;

	}


	// 組合資料

	public static function order_input_data_bind( $htmlData, $OriData )
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

			$htmlData["out_warehouse_category"]["value"] = !empty($OriData["category"]) ? $OriData["category"] : "" ;

		}

		return $htmlData;

	}


	// 訂單匯出格式

	protected function order_output_format( $data )
	{

		$_this = new self();

		$result = array();

		$status_txt = $_this->status_txt;

		if ( !empty($data) ) 
		{

			$result = array(
						"order_number_txt" 			=> !empty($data->ori_order_number) ? $data->ori_order_number : $_this->order_number_encode( $data ),
						"order_date" 				=> $data->ori_order_date !== '0000-00-00' ? $data->ori_order_date : date("Y-m-d", strtotime($data->created_at)),
						"order_status" 				=> !empty($data->ori_order_status) ? $data->ori_order_status : $status_txt[$data->status] ,
						"pay_type" 					=> !empty($data->pay_type) ? $data->pay_type : "",
						"pay_status" 				=> !empty($data->pay_status) ? $data->pay_status : "",
						"currency" 					=> $data->currency,
						"sub_total" 				=> $data->sub_total,
						"post_fee" 					=> $data->post_fee,
						"attr_fee" 					=> $data->attr_fee,
						"discount" 					=> $data->discount_fee,
						"total" 					=> $data->total,
						"remark" 					=> $data->remark,
						"send_type" 				=> !empty($data->send_type) ? $data->send_type : "",
						"send_status" 				=> !empty($data->send_status) ? $data->send_status : "",
						"receiver" 					=> $data->receiver,
						"receiver_phone" 			=> $data->receiver_phone,
						"receiver_address1" 		=> $data->receiver_address1,
						"receiver_address2" 		=> $data->receiver_address2,
						"receiver_city" 			=> $data->receiver_city,
						"receiver_section" 			=> $data->receiver_section,
						"receiver_country" 			=> $data->receiver_country,
						"receiver_postcode" 		=> $data->post_code,
						"admin_remark" 				=> $data->admin_remark,
						"send_remark" 				=> $data->send_remark,
						"receiver_store_name" 		=> $data->receiver_store_name,
						"receipt_number" 			=> $data->receipt_number,
						"receipt_address" 			=> $data->receipt_address,
						"product_code" 				=> $data->product_code,
						"product_name" 				=> !empty($data->ori_product_name) ? $data->ori_product_name : $data->product_name,
						"option" 					=> $data->option,
						"number" 					=> $data->number,
					);

		}

		return $result;

	}

}
