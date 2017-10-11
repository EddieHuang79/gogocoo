<?php

namespace App\logic;

use App\model\Shop;
use Illuminate\Support\Facades\Session;
use App\logic\Web_cht;
use App\logic\Mall_logic;
use App\logic\Store_logic;
use Ecpay;

class Shop_logic extends Basetool
{

    // 商城商品列表

    public static function shop_product_list()
    {

        return Shop::shop_product_list();

    }


    // 購買服務

    public static function service_buy( $data )
    {

        $result = false;

        $Login_user = Session::get('Login_user');

        // 找出從屬於此的服務

        if ( !empty($data) && is_array($data) ) 
        {

          $insert_data = array(
                           "service_id"    => !empty($data["service_id"]) ? intval($data["service_id"]) : "",
                           "user_id"       => intval($Login_user["user_id"])
                     );

          Shop::service_buy( $insert_data );

          $result = true;

        }

        return $result;

    }


    // 購物記錄

    public static function shop_record()
    {

      $_this = new self();

      $store_id = Session::get('Store');

      $result = array();

      $mall_shop_id = array();

      $txt = Web_cht::get_txt();

      $data = array(
                       "store_id"       => intval($store_id)
                 );

      $data = Shop::shop_record( $data );

      foreach ($data as $row) 
      {

          if ( is_object($row) ) 
          {

              $mall_shop_id[] = $row->id;

          }

      }

      // 關聯服務

      $mall_rel = Mall_logic::get_mall_service_rel( $mall_shop_id );

      foreach ($data as $row) 
      {

          if ( is_object($row) ) 
          {

            $include_service = array();

            foreach ($mall_rel[$row->id] as $shop_id => $include_data) 
            {

                $include_service[$shop_id] = $include_data;
            
            }

            $result[] = array(
                            "mall_product_id"           => $row->id,
                            "mall_product_name"         => $row->product_name,
                            "MerchantTradeNo"           => $_this->order_number_encode( $row ) ,
                            "number"                    => $row->number,
                            "paid_at"                   => $row->paid_at,
                            "cost"                      => $row->cost,
                            "total"                     => $row->cost * $row->number,
                            "status"                    => $row->status,
                            "status_txt"                => $row->status < 1 ? $txt["not_active"] : $txt["enable"] ,
                            "include_service"           => $include_service,
                          );
            }

      }

      $result = array(
                  "data"    => $data,
                  "result"  => $result,
                );

      return $result;

    }


    // 取得商城商品

    public static function get_mall_product( $mall_shop_id )
    {

        $spec = array();

        $include_service = array();

        $result = array();

        if ( !empty($mall_shop_id) && is_int($mall_shop_id) ) 
        {

          $mall_shop_id = intval($mall_shop_id);

          $data = Shop::get_mall_product( $mall_shop_id );

          // 關聯服務

          $mall_rel = Mall_logic::get_mall_service_rel( array($mall_shop_id) );

          foreach ($mall_rel[$mall_shop_id] as $shop_id => $include_data) 
          {

            $include_service[$shop_id] = $include_data;
          
          }

          foreach ($data as $row) 
          {

            if ( is_object($row) ) 
            {

              $result = array(
                              "mall_shop_id"              => $row->id,
                              "mall_product_name"         => $row->product_name,
                              "mall_product_description"  => $row->description,
                              "cost"                      => $row->cost,
                              "include_service"           => $include_service,
                            );

            }

          }

        }
        
        return $result;

    }


    // 訂單格式

    public static function order_format( $data )
    {

        $_this = new self();

        $result = array();

        if ( !empty($data) && is_array($data) ) 
        {

          $store_id = Session::get('Store');

          $MerchantTradeNo = $_this->get_mall_order_number( $store_id );

          $result = array(
                        "mall_shop_id"          => $data['mall_shop_id'],
                        "MerchantTradeNo"       => $MerchantTradeNo,
                        "store_id"              => $store_id,
                        "cost"                  => $data["cost"],
                        "number"                => $data['buy_number'],
                        "total"                 => (int)$data['buy_number'] * (int)$data["cost"],
                        "status"                => 0, // 預設未付
                        "paid_at"               => date("Y-m-d H:i:s"),
                        "created_at"            => date("Y-m-d H:i:s"),
                        "updated_at"            => date("Y-m-d H:i:s")
                    );

        }

        return $result;

    }


    // 使用資料格式

    public static function use_data_format( $data, $mall_product )
    {

        $store_id = Session::get('Store');

        $result = array();

        if ( !empty($data) && is_array($data) && !empty($mall_product) && is_array($mall_product) ) 
        {

          foreach ($mall_product["include_service"] as $mall_product_id => $row) 
          {

              $cnt = $mall_product["buy_number"] * $row["number"];

              for ($i=0; $i < $cnt; $i++) 
              { 

                  $result[] = array(
                                "store_id"              => $store_id,
                                "mall_record_id"        => $data["record_id"],
                                "mall_shop_id"          => $data["mall_shop_id"],
                                "mall_product_id"       => $mall_product_id,
                                "type"                  => 0,
                                "active_item_id"        => 0,
                                "status"                => 1,
                                "use_time"              => "0000-00-00 00:00:00"
                            );

              }

          }

        }

        return $result;

    }


    // 寫入資料

    public static function shop_buy_insert( $data )
    {

      $_this = new self();

      $txt = Web_cht::get_txt();

      $result = array();

      Session::forget('record_id');

      if ( !empty($data) && is_array($data) ) 
      {

        $data = array(
                      "mall_product_number"   => isset($data["mall_product_number"]) ? intval($data["mall_product_number"]) : 0,
                      "mall_shop_id"          => isset($data["mall_shop_id"]) ? intval($data["mall_shop_id"]) : 0,
                      "Price"                 => isset($data["total"]) ? intval($data["total"]) : 0
                  );

        $mall_product = $_this->get_mall_product( $data["mall_shop_id"] );

        $mall_product["buy_number"] = $data["mall_product_number"];

        // 主資料格式

        $insert_record = $_this->order_format( $mall_product );

        // INSERT 主資料

        $record_id = Shop::shop_buy_insert( $insert_record );

        $insert_record["record_id"] = (int)$record_id;

        Session::put("record_id", (int)$record_id);

        $use_data = $_this->use_data_format( $insert_record, $mall_product );

        $use_record_id = Shop::add_use_record( $use_data );

        $record_data = $_this->get_single_record_data( $record_id );

        // 呼叫金流付款

        $option = array(
                    "id"                  =>  $record_id,
                    "MerchantTradeNo"     =>  $_this->order_number_encode( $record_data ),
                    "mall_product_name"   =>  $mall_product["mall_product_name"],
                    "mall_product_desc"   =>  $mall_product["mall_product_description"],
                    "Price"               =>  $data["Price"],
                    "Quantity"            =>  $data["mall_product_number"],
                  );

        $_this->Call_Payment( $option );

      }

      return $result;

    }


    // 檢查使用紀錄的正確性(防止啟用到非自己所有的服務)

    public static function check_legal( $store_id, $data )
    {

        $result = array();

        if ( !empty($store_id) && is_int($store_id) && !empty($data) && is_array($data) ) 
        {

          $result = Shop::check_legal( $store_id, $data );

        }

        return $result;

    }


    // 加入使用紀錄

    public static function add_use_record( $item_id, $data, $type )
    {

        $_this = new self();

        $result = array();

        if ( !empty($item_id) && is_int($item_id) && !empty($data) && is_array($data) && !empty($type) && is_int($type) ) 
        {

          $data = explode("-", $data);

          $txt = Web_cht::get_txt();

          $store_id = Session::get('Store');

          $use_id = $_this->check_legal( $store_id, $data );

          if ( $use_id > 0 ) 
          {

            $data = array(
                             "type"             => intval($type),
                             "active_item_id"   => intval($item_id),
                             "status"           => 2,
                             "use_time"         => date("Y-m-d H:i:s"),
                       );

            $insert_result = Shop::active_use_record( $data, $use_id );

            $result = array(
                        "subject" => $insert_result ? $txt["service_use_title_success"] : $txt["service_use_title_fail"] ,
                        "content" => $insert_result ? $txt["service_use_content_success"] : $txt["service_use_content_fail"]
                      );

          }

        }

        return $result;

    }


    // 取得已使用的購買品項

    public static function get_mall_product_use_record( $store_id, $item_id, $action_key )
    {

        $_this = new self();

        $result = array();

        if ( !empty($store_id) && is_int($store_id) && !empty($item_id) && is_array($item_id) && !empty($action_key) ) 
        {

          switch ($action_key) 
          {

            case 'create_shop':
              $type = 2;
              $action_key = "";
              break;

            case 'child_account':
              $type = 1;
              $action_key = "";
              break;

            case 'extend_deadline':
              $type = 0;
              $action_key = $action_key;
              break;

          }

          $valuable_id = $_this->get_valuable_id( $store_id, $type );

          $already_add_free_deadline = array();

          $data = Shop::get_mall_product_use_record( $store_id, $item_id, $action_key, $type );

          if ( $data->count() > 0 ) 
          {

            // 加入日期規格

            $data = $_this->add_date_spec_attribute( $data );

            foreach ($data as &$row) 
            {

              if ( !in_array($row->active_item_id, $valuable_id) && !in_array($row->active_item_id, $already_add_free_deadline) ) 
              {

                  $row->date_spec+= 30 ;

                  $already_add_free_deadline[] = $row->active_item_id;

              }

            }

            $result = $data;

          }

        }

        return $result;

    }


    // 計算指定項目的截止日期(帳號、店鋪)

    public static function count_deadline( $item_id, $action_key )
    {

        $_this = new self();

        $result = array();

        $store_id = Session::get('Store');

        if ( !empty($item_id) && is_array($item_id) && !empty($action_key) ) 
        {

          $data = $_this->get_mall_product_use_record( (int)$store_id, $item_id, $action_key );

          if ( !empty($data) && $data->count() > 0 ) 
          {

            foreach ($data as $row) 
            {

                if ( is_object($row) ) 
                {

                    $result[$row->active_item_id] = isset($result[$row->active_item_id]) ? intval($result[$row->active_item_id]) : 0 ;
                    
                    $result[$row->active_item_id]+= intval($row->date_spec);
                
                    if ( $row->active_item_id == 1 ) 
                    {

                      $result[$row->active_item_id] = 9999;

                    }

                }

            }

          }

        }

        return $result;

    }


    // 取得擴展期限的選項

    public static function get_extend_deadline_option()
    {

        $_this = new self();

        $result = array();

        $use_status = array();

        $txt = Web_cht::get_txt();

        $store_id = Session::get('Store');

        // 未使用的擴展數

        $use_cnt = $_this->get_not_use_extend( $store_id, "extend_deadline" );

        foreach ($use_cnt as $row) 
        {
          
            $key = $_this->made_key(array($row->mall_shop_id, $row->mall_product_id, $row->date_spec));

            $use_status[$key] = isset($use_status[$key]) ? $use_status[$key] : 0 ;

            $use_status[$key]++;

        }

        $buy_spec_data = array();

        if ( !empty($use_status) ) 
        {

            foreach ($use_status as $index => $row) 
            {

              $tmp = explode("-", $index);

              $buy_spec_data[$index] = $tmp[2].$txt["day_unit"];

            }

            $result = $buy_spec_data;

        }

        return $result;

    }


    // 取得已購買的品項

    public static function get_data_by_action_key( $action_key )
    {

        $store_id = Session::get('Store');

        $result = array();

        if ( !empty($action_key) ) 
        {

            $result = Shop::get_shop_record_by_id( $store_id, $action_key );

        }

        return $result;

    }


    // 過濾重複規格

    public static function get_date_spec_unique_array( $data )
    {

        $_this = new self();

        $result = array();

        if ( !empty($data) && is_object($data) ) 
        {

          $key = $_this->made_key( array($data->mall_shop_id, $data->mall_product_id, $data->date_spec) );

          $result[$key] = $data->date_spec;

          $result = !empty($result) ? array_filter($result, "intval") : $result ;

        }

        return $result;

    }


    // 計算指定服務已購買的個數

    public static function get_count_by_action_key( $action_key )
    {

        $_this = new self();

        $result = array();

        if ( !empty($action_key) ) 
        {

          $data = $_this->get_data_by_action_key( $action_key );

          if ( $data->count() > 0 ) 
          {

            foreach ($data as $row) 
            {

                $key = $_this->made_key( array($row->mall_shop_id, $row->mall_product_id, $row->date_spec) );

                $result[$key]["count"] = isset($result[$key]["count"]) ? $result[$key]["count"] : 0 ;

                $result[$key]["count"]+= $row->number * $row->buy_number ;

                $result[$key]["data"] = $_this->get_date_spec_unique_array( $row );

            }

          }

        }

        return $result;

    }


    // 從關聯表建立日期規格陣列

    protected function get_mall_product_rel_data()
    {

        $_this = new self();

        $result = array();

        $data = Shop::get_mall_product_rel_data();

        if ( $data->count() > 0 ) 
        {

          foreach ($data as $row) 
          {

              $key = $_this->made_key( array($row->mall_shop_id, $row->mall_product_id) );

              $result[$key] = $row->date_spec;

          }

        }

        return $result;

    }


    // 從關聯表建立日期規格陣列

    protected function get_valuable_id( $store_id, $type )
    {

        $result = array();

        if ( !empty($store_id) && is_int($store_id) && !empty($type) && is_int($type) ) 
        {

          $data = Shop::get_valuable_id( $store_id, $type );

          if ( $data->count() > 0 ) 
          {

            foreach ($data as $row) 
            {

                $result[] = $row->active_item_id;

            }

          }

        }

        return $result;

    }


    // 增加規格屬性

    protected function add_date_spec_attribute( $data )
    {

        $_this = new self();

        $result = array();

        if ( !empty($data) ) 
        {

          $date_spec = $_this->get_mall_product_rel_data();

          if ( $data->count() > 0 ) 
          {

            foreach ($data as &$row) 
            {

              if ( is_object($row) ) 
              {

                $new_key = $_this->made_key( array($row->mall_shop_id, $row->mall_product_id) );

                $row->date_spec = isset($date_spec[$new_key]) ? $date_spec[$new_key] : 0 ;

              }

            }

            $result = $data;

          }

        }

        return $result;

    }


    // 從關聯表建立日期規格陣列

    protected function get_not_use_extend( $store_id, $action_key )
    {

        $_this = new self();

        $result = array();

        if ( !empty($store_id) && is_int($store_id) && !empty($action_key) ) 
        {

          $data = Shop::get_not_use_extend( $store_id, $action_key );

          if ( $data->count() > 0 ) 
          {

            // 加入日期規格

            $data = $_this->add_date_spec_attribute( $data );

            $result = $data;

          }

        }

        return $result;

    }


    // 返回deadline

    public static function get_deadline( $data, $extend_deadline_record )
    {

        $_this = new self();

        $result = false;

        if ( !empty($data) && is_object($data) ) 
        {

          $created_at = strtotime($data->created_at);

          $buy_date_spec = isset($extend_deadline_record[$data->id]) ? $extend_deadline_record[$data->id] : 30 ;

          if ( $data->id == 1 ) 
          {

            $buy_date_spec = 9999;

          }

          $result = date("Y-m-d", mktime(0,0,0,date("m",$created_at),date("d",$created_at) + $buy_date_spec,date("Y",$created_at)));

        }
       
        return $result;

    }


    // 取得訂單編號

    public static function get_mall_order_number( $store_id )
    {

        $_this = new self();

        $result = 1;

        if ( !empty($store_id) && is_int($store_id) ) 
        {

          $data_cnt = $_this->get_record_cnt( $store_id );

          $result = !empty($data_cnt) && is_object($data_cnt) ? (int)$data_cnt->MerchantTradeNo + 1 : 1 ;

        }

        return $result;

    }


    // 取得紀錄筆數

    public static function get_record_cnt( $store_id )
    {

        $result = array();

        if ( !empty($store_id) && is_int($store_id) ) 
        {

          $result = Shop::get_record_cnt( $store_id );

        }

        return $result;

    }


    // 訂單編號 編碼

    public static function order_number_encode( $order_data )
    {

        $result = false;

        if ( !empty($order_data) && is_object($order_data) ) 
        {

          $data = Store_logic::get_single_store( $order_data->store_id );

          $store_code = is_object($order_data) ? $data->store_code : "" ;

          $result = is_object($order_data) ? $store_code . date("Ymd", strtotime($order_data->created_at)) . str_pad($order_data->MerchantTradeNo, 8, "0", STR_PAD_LEFT) : false ;

        }

        return $result;

    }


    // 訂單編號 解碼

    public static function order_number_decode( $order_number )
    {

        $result = false;

        if ( !empty($order_number) ) 
        {

          $result = array(
                      "store_code"    =>  substr($order_number, 0, 3),
                      "order_number"  =>  substr($order_number, 11),
                      "created_at"    =>  substr($order_number, 3, 8)
                    );


          $result = !empty($result) ? $result : false ;

        }

        return $result;

    }


    // 呼叫付款api

    public static function Call_Payment( $data )
    {

        //基本參數(請依系統規劃自行調整)
        Ecpay::i()->Send['ReturnURL']         = url('/')."/DataReceive" ;
        Ecpay::i()->Send['ClientBackURL']     = url('/')."/buy_record" ;
        Ecpay::i()->Send['MerchantTradeNo']   = $data["MerchantTradeNo"];               //訂單編號
        Ecpay::i()->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                    //交易時間
        Ecpay::i()->Send['TotalAmount']       = $data["Price"];                         //交易金額
        Ecpay::i()->Send['TradeDesc']         = $data["mall_product_desc"];             //交易描述
        Ecpay::i()->Send['ChoosePayment']     = \ECPay_PaymentMethod::ALL ;             //付款方式

        //訂單的商品資料
        array_push(Ecpay::i()->Send['Items'], array('Name' => $data["mall_product_name"], 'Price' => (int)$data["Price"],
                   'Currency' => "元", 'Quantity' => (int)$data["Quantity"], 'URL' => "dedwed"));

        //Go to ECPay
        echo "緑界頁面導向中...";
        echo Ecpay::i()->CheckOutString();

    }


    // 接收付款完成

    public static function DataReceive( $data )
    {

        $_this = new self();

        $result = true;

        try 
        {
         
            if ( empty($data) || !is_array($data) ) 
            {

                $error_msg = "資料格式錯誤！";

                throw new \Exception($error_msg);

            }


            if ( !isset($data["MerchantID"]) || ( isset($data["MerchantID"]) && $data["MerchantID"] != env('PAY_MERCHANT_ID') ) ) 
            {

                $error_msg = "特店編號錯誤！";

                throw new \Exception($error_msg);

            }


            if ( !isset($data["MerchantTradeNo"]) ) 
            {

                $error_msg = "未回傳訂單編號！";

                throw new \Exception($error_msg);

            }


            if ( !isset($data["RtnCode"]) || ( isset($data["RtnCode"]) && (int)$data["RtnCode"] !== 1 ) ) 
            {

                $error_msg = "授權失敗！";

                throw new \Exception($error_msg);

            }


            // 解碼

            $order_number_data = $_this->order_number_decode( $data["MerchantTradeNo"] );


            if ( empty($order_number_data) || !is_array($order_number_data) ) 
            {

                $error_msg = "訂單編號為空||訂單解碼失敗！";

                throw new \Exception($error_msg);                

            }


            $mac = $_this->get_mac( $order_number_data );

            if ( !isset($data["CheckMacValue"]) || !isset($mac["mac"]) || ( isset($data["CheckMacValue"]) && $data["CheckMacValue"] == $mac["mac"] ) ) 
            {

                $error_msg = "mac錯誤||訂單編號錯誤||訂單狀態錯誤！";

                throw new \Exception($error_msg);

            }

            // 過濾變數

            $data = $_this->PaymentData_format( $data );

        } 
        catch (\Exception $e) 
        {

            $result = false;

            // 過濾變數

            $data = $_this->PaymentData_format( $data );

            $data["sysMsg"] = $e->getMessage();

        }

        // 寫入紀錄

        $payment_id = $_this->add_payment_data( $data );

        if ( $result === true ) 
        {

            $_this->active_mall_service_process( $payment_id );

        }

        return $result;

    }


    // 金流商回傳通知

    public static function PaymentData_format( $data )
    {
     
        $result = array();

        if ( !empty($data) && is_array($data) ) 
        {

          $result = array(

                      "MerchantTradeNo"       =>  isset($data['MerchantTradeNo']) ? trim($data['MerchantTradeNo']) : "" ,
                      "RtnCode"               =>  isset($data['RtnCode']) ? intval($data['RtnCode']) : "" ,
                      "RtnMsg"                =>  isset($data['RtnMsg']) ? trim($data['RtnMsg']) : "" ,
                      "TradeNo"               =>  isset($data['TradeNo']) ? trim($data['TradeNo']) : "" ,
                      "TradeAmt"              =>  isset($data['TradeAmt']) ? intval($data['TradeAmt']) : "" ,
                      "PaymentDate"           =>  isset($data['PaymentDate']) ? date("Y-m-d H:i:s", strtotime($data['PaymentDate'])) : "" ,
                      "PaymentType"           =>  isset($data['PaymentType']) ? trim($data['PaymentType']) : "" ,
                      "PaymentTypeChargeFee"  =>  isset($data['PaymentTypeChargeFee']) ? intval($data['PaymentTypeChargeFee']) : "" ,
                      "TradeDate"             =>  isset($data['TradeDate']) ? date("Y-m-d H:i:s", strtotime($data['TradeDate'])) : "" ,
                      "CheckMacValue"         =>  isset($data['CheckMacValue']) ? trim($data['CheckMacValue']) : "" ,
                      "sysMsg"                =>  isset($data['sysMsg']) ? trim($data['sysMsg']) : "" ,
                      "created_at"            =>  date("Y-m-d H:i:s") ,
                      "updated_at"            =>  date("Y-m-d H:i:s") 

                  );

        }

        return $result;

    }


    // 寫入付款資料

    public static function add_payment_data( $data )
    {

        $result = false;

        if ( !empty($data) && is_array($data) ) 
        {

            $result = Shop::add_payment_data( $data );

        }

        return $result;

    }


    // 取得付款資料

    public static function get_payment_data( $payment_id )
    {

        $result = array();

        if ( !empty($payment_id) && is_int($payment_id) ) 
        {

            $result = Shop::get_payment_data( $payment_id );

        }

        return $result;

    }


    // 啟用服務

    public static function active_mall_service_process( $payment_id )
    {

        $_this = new self();

        $result = false;

        if ( !empty($payment_id) && is_int($payment_id) ) 
        {

            $data = $_this->get_payment_data( $payment_id );

            $MerchantTradeNo = is_object($data) ? $data->MerchantTradeNo : "" ;

            $store_code = substr( $MerchantTradeNo, 0, 3 );

            $store_obj = Store_logic::get_store_id_by_code( $store_code );

            $store_id = is_object($store_obj) ? (int)$store_obj->id : 0 ;

            $created_at = strtotime( substr( $MerchantTradeNo, 3, 8 ) );

            $seq = (int)substr( $MerchantTradeNo, 11, 8 );

            $data = array(

                      "store_id"          => $store_id,
                      "seq"               => $seq,
                      "created_at"        => $created_at,
                      "PaymentDate"       => $data->PaymentDate

                    );

            $result = $_this->active_mall_service( $data );

        }

        return $result;

    }


    // 啟用服務

    public static function active_mall_service( $data )
    {

        $result = false;

        if ( !empty($data) && is_array($data) ) 
        {

            Shop::active_mall_service( $data );

            $result = true;

        }

        return $result;

    }


    // 取得單筆訂單紀錄

    public static function get_single_record_data( $record_id )
    {

      $result = array();

      if ( !empty($record_id) && is_int($record_id) ) 
      {

          $result = Shop::get_single_record_data( $record_id );

      }

      return $result;

    }


    // 儲存驗證碼

    public static function set_mac( $record_id, $mac )
    {

        $result = false;

        if ( !empty($record_id) && is_int($record_id) && !empty($mac) ) 
        {

            Shop::set_mac( $record_id, $mac );

            $result = true;

        }


        return $result;

    }


    // 取得驗證碼

    public static function get_mac( $data )
    {

        $result = array();

        if ( !empty($data) && is_array($data) ) 
        {

            $result = Shop::get_mac( $data );

        }


        return $result;

    }


}
