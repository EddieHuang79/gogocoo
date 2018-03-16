<?php

namespace App\logic;

use App\model\Shop;
use Illuminate\Support\Facades\Session;
use App\logic\Web_cht;
use App\logic\Mall_logic;
use App\logic\Store_logic;
use App\logic\Promo_logic;
use Ecpay;
use App\Mail\FirstBuyGift;
use Mail;
use App\logic\Record_logic;
use App\logic\Ecoupon_logic;

class Shop_logic extends Basetool
{

    protected $property_type = array();
        
    protected $property_status = array();

    public function __construct()
    {

      // 文字

      $txt = Web_cht::get_txt();

      $this->property_type = array(
                1   =>  $txt["property_service"],
                2   =>  $txt["property_ecoupon"]
              );

      $this->property_status = array(
                1   =>  $txt["property_status1"],
                2   =>  $txt["property_status2"],
                3   =>  $txt["property_status3"],
                4   =>  $txt["property_status4"],
                5   =>  $txt["property_status5"]
              );

    } 

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

      // 取得Ecoupon 使用記錄

      $ecoupon_record = Ecoupon_logic::get_ecoupon_use_record( $store_id );

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
                            "discount"                  => isset($ecoupon_record[$row->record_id]) ? $ecoupon_record[$row->record_id] : 0,
                            "total"                     => $row->total,
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
                              "promo"                     => Promo_logic::get_active_promo_price( $row->id ),
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
                        "cost"                  => $data['cost'],
                        "number"                => $data['buy_number'],
                        "total"                 => $data['total'],
                        "status"                => $data['status'], // 預設未付
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

      $result = "/buy_record";

      Session::forget('record_id');

      if ( !empty($data) && is_array($data) ) 
      {

          $data = array(
                        "mall_product_number"   => isset($data["mall_product_number"]) ? intval($data["mall_product_number"]) : 0,
                        "mall_shop_id"          => isset($data["mall_shop_id"]) ? intval($data["mall_shop_id"]) : 0,
                        "Ecoupon_code"          => isset($data["Ecoupon"]) ? trim($data["Ecoupon"]) : "",
                        "total"                 => isset($data["total"]) ? intval($data["total"]) : 0
                    );

          try 
          {


              if ( empty($data["mall_shop_id"]) || empty($data["mall_product_number"]) ) 
              {

                  throw new \Exception( "購買商品資訊錯誤" );

              }

              $mall_product = $_this->get_mall_product( $data["mall_shop_id"] );

              if ( empty($mall_product) ) 
              {

                  throw new \Exception( "偵測不到對應商品" );

              }

              $mall_product["buy_number"] = $data["mall_product_number"];


              // 利用mall_shop_id + mall_product_number計算小計

              $sub_total = $_this->get_sub_total( $mall_product );

              $count_discount_price = array(
                                        "discount_price" => 0
                                      );

              // 折價券驗證

              if ( !empty($data["Ecoupon_code"]) ) 
              {

                $test_ecoupon = Ecoupon_logic::test_ecoupon_code( $data["Ecoupon_code"] );

                // 取得折扣負項

                $count_discount_price = Ecoupon_logic::get_ecoupon_discount_price( $test_ecoupon["data"]["type"], $test_ecoupon["data"]["ecoupon_content"], $sub_total );
                
              }

              $total = $sub_total + $count_discount_price["discount_price"] ;

              if ( $total !== $data["total"] ) 
              {

                  throw new \Exception( "總價計算參數與傳入參數不同" );

              }

              // 設定必要值

              $mall_product["cost"] = $mall_product["promo"] > 0 ? $mall_product["promo"] : $mall_product["cost"] ;

              $mall_product["status"] = $total <= 0 ? 1 : 0 ;

              $mall_product["total"] = $total;

              // 主資料格式

              $insert_record = $_this->order_format( $mall_product );

              // INSERT 主資料

              $record_id = Shop::shop_buy_insert( $insert_record );

              $insert_record["record_id"] = (int)$record_id;

              Session::put("record_id", (int)$record_id);

              $use_data = $_this->use_data_format( $insert_record, $mall_product );

              $use_record_id = Shop::add_use_record( $use_data );

              $record_data = $_this->get_single_record_data( $record_id );

              if ( !empty($data["Ecoupon_code"]) ) 
              {

                // 註銷已使用的折價券

                Ecoupon_logic::inactive_ecoupon_use_status( $data["Ecoupon_code"] );

                // 寫入使用記錄

                $use_data_array = array(
                                    "record_id"         => $record_id,
                                    "ecoupon_use_id"    => $test_ecoupon["data"]["ecoupon_use_id"],
                                    "store_id"          => $test_ecoupon["data"]["store_id"],            
                                    "discount"          => $count_discount_price["discount_price"]            
                                  );

                $use_data = Ecoupon_logic::insert_record_format( $use_data_array );

                Ecoupon_logic::add_ecoupon_use_record( $use_data );

              }

              // 付款金額大於0，呼叫金流付款

              if ( $mall_product["total"] > 0 ) 
              {

                  $option = array(
                              "id"                  =>  $record_id,
                              "MerchantTradeNo"     =>  $_this->order_number_encode( $record_data ),
                              "mall_product_name"   =>  $mall_product["mall_product_name"],
                              "mall_product_desc"   =>  $mall_product["mall_product_description"],
                              "Price"               =>  $data["total"],
                              "Quantity"            =>  $data["mall_product_number"],
                              "discount_price"      =>  $count_discount_price,
                            );

                  $_this->Call_Payment( $option );
                
              }
              else
              {

                  // 免費的狀況，暫時不判斷首購，直接導去記錄頁

                  return "/buy_record";

              }


          } 
          catch (\Exception $e) 
          {

              $data = array(
                        "msg"   => $e->getMessage(),
                        "data"  => $data
                      );
            
              Record_logic::write_log( "ShopError", json_encode($data) );

              return "/buy_record";

          }


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

        if ( !empty($item_id) && is_int($item_id) && !empty($data) && is_string($data) && !empty($type) && is_int($type) ) 
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

            // if ( !isset($data["CheckMacValue"]) || !isset($mac["mac"]) || ( isset($data["CheckMacValue"]) && $data["CheckMacValue"] == $mac["mac"] ) ) 
            if ( !isset($data["CheckMacValue"]) || !isset($mac->mac) ) 
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


            // 首購判斷觸發

            $_this->first_buy_gift_logic( (int)$payment_id );

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

            $data = $_this->MerchantTradeNo_decode( $payment_id );

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


    // 判斷是否首購

    public static function is_first_buy( $data )
    {

        $result = false;

        if ( !empty($data) && is_array($data) ) 
        {

            $find_data = Shop::is_first_buy( $data );

            $result = $find_data[0]->paid_at === $data["PaymentDate"] ? true : false;

        }

        return $result;

    }


    // 寫入贈送商品

    public static function add_free_gift( $store_id, $mall_shop_id )
    {

        $_this = new self();

        $result = false;

        if ( !empty($store_id) && is_int($store_id) && !empty($mall_shop_id) && is_int($mall_shop_id) ) 
        {

            $data = array(
                          "mall_product_number"   => 1,
                          "mall_shop_id"          => $mall_shop_id,
                          "Price"                 => 0
                      );

            $mall_product = $_this->get_mall_product( $data["mall_shop_id"] );

            $mall_product["buy_number"] = $data["mall_product_number"];

            // 贈送商品設定為已付款

            $mall_product["status"] = 1;

            // 主資料格式

            $insert_record = $_this->order_format( $mall_product );

            // 覆寫store_id

            $insert_record["store_id"] = $store_id;

            // INSERT 主資料 TO Record

            $record_id = Shop::shop_buy_insert( $insert_record );

            $insert_record["record_id"] = (int)$record_id;

            $use_data = $_this->use_data_format( $insert_record, $mall_product );

            // 複寫store_id

            $use_data[0]["store_id"] = $store_id;

            $use_record_id = Shop::add_use_record( $use_data );

            $result = true;

        }

        return $result;

    }


    // 首購通知信

    public static function send_first_buy_mail( $user_data )
    {

        $result = false;

        if ( !empty($user_data) && is_object($user_data) ) 
        {

            $mail_data = Edm_logic::insert_FirstBuyGift_mail_format( $user_data );

            Edm_logic::add_edm( $mail_data );

            $result = true;

        }

        return $result;

    }


    // Logic

    public static function first_buy_gift_logic( $payment_id )
    {

        $_this = new self();

        $result = false;

        if ( !empty($payment_id) && is_int($payment_id) ) 
        {

            $data = $_this->MerchantTradeNo_decode( $payment_id );

            $is_first_buy = $_this->is_first_buy( $data );

            if ( $is_first_buy === true ) 
            {

                $user = Admin_user_logic::get_user_by_store_id( (int)$data["store_id"] );

                // 首購id : 1

                $_this->add_free_gift( (int)$data["store_id"], 1 );

                $_this->send_first_buy_mail( $user );

                // 寫入贈送訊息

                $subject = "首購禮已贈送！";

                $content = "請前往購買紀錄查看！";

                Msg_logic::add_normal_msg( $subject, $content, $user->id );

            }

            $result = true;

        }

        return $result;

    }


    // 列表呈現用的資料

    public static function get_html_data( $data )
    {

      $result = array();

      if ( !empty($data) && $data->isNotEmpty() ) 
      {

          foreach ($data as $row) 
          {

              $result[] = array(
                            "id"            => $row->id,
                            "product_name"  => $row->product_name,
                            "pic"           => $row->pic
                          );

          }
        
      }

      return $result;

    }


    // 組合列表資料

    public static function shop_record_list_data_bind( $OriData )
    {

      $_this = new self();

      $txt = Web_cht::get_txt();

      $result = array(
                      "title" => array(
                              $txt['mall_order_number'],
                              $txt['product_name'],
                              $txt['include_service'],
                              $txt['number'],
                              $txt['price'],
                              $txt['ecoupon_discount'],
                              $txt['total'],
                              $txt['paid_at'],
                              $txt['status']
                            ),
                      "data" => array()
                  );

      $txt = Web_cht::get_txt();

      if ( !empty($OriData) && is_array($OriData) ) 
      {

        foreach ($OriData as $row) 
        {

          $include_service = array();

          foreach ($row["include_service"] as $row1) 
          {

              $include_service[] = $row1["product_name"] . "/" . $row1["number"] . $txt['service_unit'] . "/" . $row1["date_spec"] . $txt['day_unit'] ;
          
          }
    
          $data = array(
                "data" => array(
                        "mall_order_number"     => $row["MerchantTradeNo"],
                        "product_name"          => $row["mall_product_name"],
                        "include_service"       => $include_service,
                        "number"                => $row["number"],
                        "price"                 => $row["cost"],
                        "ecoupon_discount"      => $row["discount"],
                        "total"                 => $row["total"] >= 0 ? $row["total"] : 0 . "(" . $row["total"] . ")" ,
                        "paid_at"               => $row["paid_at"],
                        "status"                => $row["status_txt"],
                      ),
                "Editlink" => ""
              );
            
          $result["data"][] = $data;
        
        }


      }

      return $result;

    }


    // 取得財產列表資料

    public static function get_property_list()
    {

      $_this = new self();

      $txt = Web_cht::get_txt();

      $result = array();

      $store_id = Session::get('Store');

      // 取得商品規格

      $product_rel = $_this->get_mall_product_rel_array();

      if ( !empty($store_id) ) 
      {

        $tmp = array();

        // 取得所有已付款服務

        $inactive_service = $_this->record_and_use( array($store_id) );

        foreach ($inactive_service as $row) 
        {

            $property_status = (int)$row["pay_status"] === 0 ? 1 : "" ;
            $property_status = (int)$row["pay_status"] === 1 && (int)$row["use_status"] === 1 ? 2 : $property_status ;
            $property_status = (int)$row["use_status"] === 2 ? 3 : $property_status ;

            $date_spec = isset($product_rel[ $row["mall_shop_id"] . "-" . $row["mall_product_id"] ]["date_spec"]) ? $product_rel[ $row["mall_shop_id"] . "-" . $row["mall_product_id"] ]["date_spec"] : 0 ;

            $property_content = $row["product_name"] . "/" . $date_spec . $txt['day_unit'] ;


            $tmp[] = array(
                        "property_name"       =>  $row["product_name"],
                        "property_type"       =>  1,
                        "property_content"    =>  $property_content,
                        "property_status"     =>  $property_status,
                        "property_use_time"   =>  $row["use_time"],
                        "remark"              =>  ""
                    );

        }

        // 取得所有折價券

        $data = Ecoupon_logic::get_user_active_ecoupon_data( $store_id );

        foreach ($data as $row) 
        {

          $tmp[] = array(
                      "property_name"       =>  $row["name"],
                      "property_type"       =>  2,
                      "property_content"    =>  Ecoupon_logic::ecoupon_content_to_string( $row ),
                      "property_status"     =>  $row["status"] === 1 ? 4 : 5 ,
                      "property_use_time"   =>  $row["status"] === 1 ? "0000-00-00 00:00:00" : $row["created_at"],
                      "remark"              =>  $row["status"] === 1 && strtotime($row["deadline"]) < strtotime("+7 days") ? "即將到期，到期日:" . $row["deadline"] : ""
                  );

        }       

        $result = $tmp;

      }


      return $result;

    }


    // 財產列表資料

    public static function shop_property_list_data_bind( $OriData )
    {

      $_this = new self();

      $txt = Web_cht::get_txt();

      $result = array(
                      "title" => array(
                              $txt['property_name'],
                              $txt['property_type'],
                              $txt['property_content'],
                              $txt['property_status'],
                              $txt['property_use_time'],
                              $txt['remark']
                            ),
                      "data" => array()
                  );

      $txt = Web_cht::get_txt();

      if ( !empty($OriData) && is_array($OriData) ) 
      {

        $property_type = $_this->property_type;

        $property_status = $_this->property_status;

        foreach ($OriData as $row) 
        {
    
          $data = array(
                "data" => array(
                        "property_name"                   => $row["property_name"],
                        "property_type"                   => isset($property_type[$row["property_type"]]) ? $property_type[$row["property_type"]] : "" ,
                        "property_content"                => $row["property_content"],
                        "property_status"                 => isset($property_status[$row["property_status"]]) ? $property_status[$row["property_status"]] : "" ,
                        "property_use_time"               => $row["property_use_time"],
                        "remark"                          => $row["remark"]
                      ),
                "Editlink" => ""
              );
            
          $result["data"][] = $data;
        
        }


      }

      return $result;

    }


    // MerchantTradeNo Decode

    protected function MerchantTradeNo_decode( $payment_id )
    {

        $_this = new self();

        $result = array();

        if ( !empty($payment_id) && is_int($payment_id) ) 
        {

            $data = $_this->get_payment_data( $payment_id );

            $MerchantTradeNo = is_object($data) ? $data->MerchantTradeNo : "" ;

            $store_code = substr( $MerchantTradeNo, 0, 3 );

            $store_obj = Store_logic::get_store_id_by_code( $store_code );

            $store_id = is_object($store_obj) ? (int)$store_obj->id : 0 ;

            $created_at = strtotime( substr( $MerchantTradeNo, 3, 8 ) );

            $seq = (int)substr( $MerchantTradeNo, 11, 8 );

            $result = array(

                      "store_id"          => $store_id,
                      "seq"               => $seq,
                      "created_at"        => $created_at,
                      "PaymentDate"       => $data->PaymentDate

                    );

        }

        return $result;

    }


    protected function get_sub_total( $data )
    {

        $result = 0;

        if ( !empty($data) && is_array($data) ) 
        {

            $data["cost"] = isset($data["cost"]) && !empty($data["cost"]) ? $data["cost"] : 0 ;

            $price = isset($data["promo"]) && !empty($data["promo"]) ? $data["promo"] : $data["cost"] ;

            $result = $data["buy_number"] * $price ;
          
        }

        return $result;

    }


    // 從關聯表建立日期規格陣列

    protected function get_mall_product_rel_array()
    {

        $_this = new self();

        $result = array();

        $data = Shop::get_mall_product_rel_data();

        if ( $data->count() > 0 ) 
        {

          foreach ($data as $row) 
          {

              $key = $_this->made_key( array($row->mall_shop_id, $row->mall_product_id) );

              $result[$key] = array(
                                "date_spec" => $row->date_spec,
                                "number"    => $row->number
                              );

          }

        }

        return $result;

    }


    // 取得服務購買紀錄與使用

    protected function record_and_use( $store_id )
    {

        $_this = new self();

        $result = array();

        if ( !empty($store_id) && is_array($store_id) ) 
        {

            $data = Shop::record_and_use( $store_id );

            foreach ($data as $index => $row) 
            {

                foreach ($row as $index2 => $value) 
                {

                    $result[$index][$index2] = $value;

                }

            }
            
        }


        return $result;

    }

}
