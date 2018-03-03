<?php

namespace App\logic;

use App\logic\Web_cht;
use Maatwebsite\Excel\Facades\Excel;
use App\logic\Msg_logic;
use App\logic\Product_logic;
use App\logic\Purchase_logic;
use App\logic\Order_logic;
use Illuminate\Support\Facades\Session;

class Upload_logic extends Basetool
{

	protected $txt;

	public function __construct()
	{

		// 文字
		$this->txt = Web_cht::get_txt();

	}


	// 欄位轉英文

	public static function column_name_to_english( $data )
	{

		$_this = new self();

		$txt = $_this->txt;

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			foreach ($data as $key => $row) 
			{

				$new_key = !empty( $key ) ? array_keys($txt, $key) : array() ;

				$new_key = !empty($new_key) ? $new_key[0] : "" ;

				if (!empty($new_key)) 
				{

					$result[$new_key] = $row;

				}

			}

		}

		return $result;

	}


	// 商品上傳格式

	public static function product_upload_format( $data )
	{

		if ( !empty($data) ) 
		{

			$_this = new self();

			$txt = $_this->txt;

			$result = array();

			$output_data = array();

			$column_header = array();

			$column_desc = array();

			$file_name = date("YmdHis")."product_upload_format";

			$title = $txt["product_upload_format"];

			foreach ($data as $row) 
			{

				$column_header[] = $txt[$row['name']];

			}

			$output_data[] = $column_header;

			// foreach ($data as $row) 
			// {

			// 	$column_desc[] = $txt[$row['name']."_upload_desc"];

			// }

			// $output_data[] = $column_desc;

			$output_data[] = Product_logic::product_upload_sample_output( $data );

			$final_column = $_this->ASC_Decimal_value( count($column_header) );

			$final_row = count($output_data);

			$result = $_this->export_excel( $output_data, $title, $final_column, $final_row, $file_name );

			$result->download('xlsx');

			exit();

		}

	}


	// 商品上傳流程

	public static function product_upload_process( $path )
	{

		$_this = new self();

		$data = Excel::load($path)->toArray();

		$Login_user = Session::get("Login_user");

		$error_data = array();

		$i = 2;

		try 
		{

			if (empty($data)) 
			{

				$error_msg = "資料無法解析，上傳失敗！";

			   throw new \Exception($error_msg);

			}

			$extra_column = Product_logic::get_product_extra_column();

			$select_option["category"] = ProductCategory_logic::get_all_category_list();

			foreach ($data as $row) 
			{

				$row = $_this->column_name_to_english( $row );

				$main_data = Product_logic::insert_main_format( $row );

				if ( !empty($main_data["product_name"]) ) 
				{

					$product_id = Product_logic::add_product( $main_data );

					// 轉成數字

					$row = $_this->upload_data_en_int( $row, $select_option );

					$extra_data = Product_logic::insert_extra_format( $row, $extra_column, $product_id );

					Product_logic::add_extra_data( $extra_data );

				}
				else
				{
					
					// 錯誤處理

					$error_data[] = $i;

				}

			}

			if (!empty($error_data)) 
			{

				$error_row = implode(",", $error_data);

				$error_msg = "第{$error_row}列資料無法解析，上傳失敗！其餘列數成功！";

			  	throw new \Exception($error_msg);

			}

			$subject = $content = "商品上傳成功！";

			Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		} 
		catch (\Exception $e) 
		{
			
    		$subject = "商品上傳失敗";

    		$content = "商品上傳失敗！失敗原因: ". $e->getMessage();

    		Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		}

	}


	// 進貨上傳格式

	public static function purchase_upload_format( $data )
	{

		if ( !empty($data) && is_array($data) ) 
		{

			$_this = new self();

			$txt = $_this->txt;

			$result = array();

			$output_data = array();

			$column_header = array();

			$file_name = date("YmdHis")."purchase_upload_format";

			$title = $txt["purchase_upload_format"];

			foreach ($data as $row) 
			{

				$column_header[] = $txt[$row['name']];

			}

			$output_data[] = $column_header;

			// foreach ($data as $row) 
			// {

			// 	$column_desc[] = $txt[$row['name']."_upload_desc"];

			// }

			// $output_data[] = $column_desc;

			$output_data[] = Purchase_logic::purchase_upload_sample_output( $data );

			$final_column = $_this->ASC_Decimal_value( count($column_header) );

			$final_row = count($output_data);

			$result = $_this->export_excel( $output_data, $title, $final_column, $final_row, $file_name );

			$result->download('xlsx');

			exit();

		}

	}


	// 進貨上傳流程

	public static function purchase_upload_process( $path )
	{

		$_this = new self();

		$data = Excel::load($path)->toArray();

		$Login_user = Session::get("Login_user");

		$error_data = array();

		$i = 2 ;

		try 
		{

			if (empty($data)) 
			{

				$error_msg = "資料無法解析，上傳失敗！";

			   throw new \Exception($error_msg);

			}

			$extra_column = Purchase_logic::get_purchase_extra_column();

			foreach ($data as $row) 
			{

				$row = $_this->column_name_to_english( $row );

				$main_data = Purchase_logic::insert_format( $row );

				if ( !empty($main_data['product_id']) ) 
				{

					$purchase_id = Purchase_logic::add_purchase( $main_data );

					$extra_data = Purchase_logic::insert_extra_format( $row, $extra_column, $purchase_id );

					Purchase_logic::add_extra_purchase( $extra_data );

				}
				else
				{
					
					// 錯誤處理

					$error_data[] = $i;

				}

				$i++;

			}


			if (!empty($error_data)) 
			{

				$error_row = implode(",", $error_data);

				$error_msg = "第{$error_row}列資料無法解析，上傳失敗！其餘列數成功！";

			  	throw new \Exception($error_msg);

			}

			$subject = $content = "進貨上傳成功！";

			Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		} 
		catch (\Exception $e) 
		{
			
    		$subject = "進貨上傳失敗";

    		$content = "進貨上傳失敗！失敗原因: ". $e->getMessage();

    		Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		}

	}


	// 訂單上傳格式

	public static function order_upload_format( $data )
	{

		if ( !empty($data) && is_array($data) ) 
		{

			$_this = new self();

			$txt = $_this->txt;

			$result = array();

			$output_data = array();

			$column_header = array();

			$file_name = date("Ymd")."order_upload_format";

			$title = $txt["order_upload_format"];

			foreach ($data as $row) 
			{

				$column_header[] = $txt[$row['name']];

			}

			$output_data[] = $column_header;

			$output_data[] = Order_logic::order_upload_sample_output( $data );

			$final_column = $_this->ASC_Decimal_value( count($column_header) );

			$final_row = count($output_data);

			$result = $_this->export_excel( $output_data, $title, $final_column, $final_row, $file_name );

			$result->download('xlsx');

			exit();

		}

	}


	// 訂單上傳流程

	public static function order_upload_process( $path )
	{

		$_this = new self();

		$data = Excel::load($path)->toArray();

		$Login_user = Session::get("Login_user");

		$error_data = array();

		$i = 2 ;

		try 
		{

			if (empty($data)) 
			{

				$error_msg = "資料無法解析，上傳失敗！";

			   throw new \Exception($error_msg);

			}

			$extra_column = Order_logic::get_order_extra_column();

			foreach ($data as $row) 
			{

				$row = $_this->column_name_to_english( $row );

				$main_data = Order_logic::insert_format( $row );

				if ( !empty($main_data['product_id']) ) 
				{

					$order_id = Order_logic::add_order( $main_data );

					$extra_data = Order_logic::insert_extra_format( $row, $extra_column, $order_id );

					Order_logic::add_extra_order( $extra_data );

				}
				else
				{
					
					// 錯誤處理

					$error_data[] = $i;

				}

				$i++;

			}


			if (!empty($error_data)) 
			{

				$error_row = implode(",", $error_data);

				$error_msg = "第{$error_row}列資料無法解析，上傳失敗！其餘列數成功！";

			  	throw new \Exception($error_msg);

			}

			$subject = $content = "訂單上傳成功！";

			Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		} 
		catch (\Exception $e) 
		{

    		$subject = "訂單上傳失敗";

    		$content = "訂單上傳失敗！失敗原因: ". $e->getMessage();

    		Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		}

	}


	// 特定欄位，將上傳的英文轉成數字

	public static function upload_data_en_int( $data, $select_option )
	{

		$_this = new self();

		$result = array();

		$target = array('category'); 

		if ( !empty( $data ) && !empty( $select_option ) )
		{

			foreach ($data as $index => &$row) 
			{

				if ( in_array($index, $target) ) 
				{

					if ( isset($select_option[$index]) ) 
					{

						$row = array_keys($select_option[$index], $row);

						$row = !empty($row) ? array_shift($row) : -1 ;

						$row = intval($row);

					}
					else
					{

						$row = -1;
					
					}

				}

			}

			$result = $data;

		}

		return $result;

	}


	// 訂單轉出

	public static function order_output( $order_id )
	{

		if ( !empty($order_id) && is_array($order_id) ) 
		{

			$_this = new self();

			$txt = $_this->txt;

			$result = array();

			$output_data = array();

			$column_header = array();

			$file_name = date("YmdHis")."order_format";

			$title = $txt["order_export_format"];

			$column_header = array(
								"訂單號碼",
								$txt["ori_order_date"],
								$txt["ori_order_status"],
								$txt["pay_type"],
								$txt["pay_status"],
								$txt["currency"],
								$txt["sub_total"],
								$txt["post_fee"],
								$txt["attr_fee"],
								$txt["discount_fee"],
								$txt["order_total"],
								$txt["order_remark"],
								$txt["send_type"],
								$txt["send_status"],
								$txt["receiver"],
								$txt["receiver_phone"],
								"地址 1",
								"地址 2",
								$txt["receiver_city"],
								"地區/州/省份",
								"地區／國家",
								"郵政編號 (如適用)",
								$txt["admin_remark"],
								$txt["send_remark"],
								$txt["receiver_store_name"],
								"統一編號",
								$txt["receipt_address"],
								$txt["product_code"],
								"商品名稱",
								$txt["option"],
								$txt["number"]
							);

			$output_data[] = $column_header;

			$tmp = Order_logic::order_output( $order_id );

			foreach ($tmp as $row) 
			{

				$output_data[] = $row;

			}

			// 待處理

			$final_column = $_this->ASC_Decimal_value( count($column_header) );

			$final_row = count($output_data);

			$result = $_this->export_excel( $output_data, $title, $final_column, $final_row, $file_name );

			$result->download('xlsx');

			exit();

		}

	}


	// 通用格式訂單上傳流程

	public static function assign_order_upload_process( $path )
	{

		$_this = new self();

		$data = Excel::load($path)->toArray();

		$Login_user = Session::get("Login_user");

		$error_data = array();

		$product_name_array = array();

		$i = 2 ;

		try 
		{

			if (empty($data)) 
			{

				$error_msg = "資料無法解析，上傳失敗！";

			   throw new \Exception($error_msg);

			}

			$extra_column = Order_logic::get_order_extra_column();


			// 檢查商品是否存在，有則回傳product_id，沒有則建立之後回傳建立編號

			foreach ($data as $row) 
			{

				$row = $_this->column_name_to_english( $row );

				$product_name_array[] = array(
											"ori_product_name" 	=> $row["ori_product_name"],
											"product_code" 		=> $row["product_code"],
										);

			}

			// Mapping id

			$product_id_mapping = Product_logic::get_product_id_or_create_product( $product_name_array );

			foreach ($data as $row) 
			{

				$row = $_this->column_name_to_english( $row );

				// 檢查商品是否存在，有則回傳product_id，沒有則建立之後回傳建立編號

				$row["product_id"] = isset($product_id_mapping[$row["ori_product_name"]]) ? $product_id_mapping[$row["ori_product_name"]] : 0 ;

				$main_data = Order_logic::insert_format( $row );

				if ( !empty($main_data['product_id']) ) 
				{

					$order_id = Order_logic::add_order( $main_data );

					$extra_data = Order_logic::insert_extra_format( $row, $extra_column, $order_id );

					Order_logic::add_extra_order( $extra_data );

				}
				else
				{
					
					// 錯誤處理

					$error_data[] = $i;

				}

				$i++;

			}


			if (!empty($error_data)) 
			{

				$error_row = implode(",", $error_data);

				$error_msg = "第{$error_row}列資料無法解析，上傳失敗！其餘列數成功！";

			  	throw new \Exception($error_msg);

			}

			$subject = $content = "訂單上傳成功！";

			Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		} 
		catch (\Exception $e) 
		{

    		$subject = "訂單上傳失敗";

    		$content = "訂單上傳失敗！失敗原因: ". $e->getMessage();

    		Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		}

	}


	// 取得輸入邏輯陣列

	public static function get_product_upload_input_template_array()
	{

		$_this = new self();

		$txt = Web_cht::get_txt();


        $htmlData = array(       
                        "product_upload" => array(
                            "type"          => 8, 
                            "title"         => $txt["product_upload"],
                            "key"           => "user_product_upload",
                            "value"         => "",
                            "display"       => true,
                            "hasPlugin"     => "",
                            "Samplelink"    => "/product_upload_format_download"
                        )
                    );

		return $htmlData;

	}


	// 取得輸入邏輯陣列

	public static function get_purchase_upload_input_template_array()
	{

		$_this = new self();

		$txt = Web_cht::get_txt();


        $htmlData = array(       
                        "purchase_upload" => array(
                            "type"          => 8, 
                            "title"         => $txt["purchase_upload"],
                            "key"           => "user_purchase_upload",
                            "value"         => "",
                            "display"       => true,
                            "hasPlugin"     => "",
                            "Samplelink"    => "/purchase_upload_format_download"
                        )
                    );

		return $htmlData;

	}


	// 取得輸入邏輯陣列

	public static function get_order_upload_input_template_array()
	{

		$_this = new self();

		$txt = Web_cht::get_txt();


        $htmlData = array(       
                        "order_upload" => array(
                            "type"          => 8, 
                            "title"         => $txt["order_upload"],
                            "key"           => "user_order_upload",
                            "value"         => "",
                            "display"       => true,
                            "hasPlugin"     => "",
                            "Samplelink"    => "/order_upload_format_download"
                        )
                    );

		return $htmlData;

	}



}
