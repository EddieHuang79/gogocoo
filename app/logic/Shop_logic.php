<?php

namespace App\logic;

use App\model\Shop;
use Illuminate\Support\Facades\Session;
use App\logic\Web_cht;
use App\logic\mall_logic;

class Shop_logic extends Basetool
{

    // protected $date_spec_array = array();

    // public function __construct()
    // {

    //   $this->date_spec_array = $this->get_mall_product_rel_data();

    // }

    public static function shop_product_list()
    {

      return Shop::shop_product_list();

    }

    public static function service_buy( $data )
    {

      $Login_user = Session::get('Login_user');

      // 找出從屬於此的服務

      $insert_data = array(
                       "service_id"    => !empty($data["service_id"]) ? intval($data["service_id"]) : "",
                       "user_id"       => intval($Login_user["user_id"])
                 );

      Shop::service_buy( $insert_data );

    }

    public static function shop_record()
    {

      $Login_user = Session::get('Login_user');

      $result = array();

      $mall_shop_id = array();

      $data = array(
                       "user_id"       => intval($Login_user["user_id"])
                 );

      $data = Shop::shop_record( $data );

      foreach ($data as $row) 
      {

        $mall_shop_id[] = $row->id;

      }

      // 關聯服務

      $mall_rel = mall_logic::get_mall_service_rel( $mall_shop_id );

      foreach ($data as $row) 
      {

        $include_service = array();

        foreach ($mall_rel[$row->id] as $shop_id => $include_data) 
        {

          $include_service[$shop_id] = $include_data;
        
        }

        $result[] = array(
                        "mall_product_id"           => $row->id,
                        "mall_product_name"         => $row->product_name,
                        // "mall_product_description"  => $row->description,
                        // "mall_product_pic"          => $row->pic,
                        // "mall_rel"                  => $include_service,
                        // "mall_product_spec"         => $spec[$row->id],
                        "number"                    => $row->number,
                        "paid_at"                   => $row->paid_at,
                        "cost"                      => $row->cost,
                        "total"                     => $row->cost * $row->number,
                        "include_service"           => $include_service,
                      );

      }

      $result = array(
                  "data"    => $data,
                  "result"  => $result,
                );

      return $result;

    }

    public static function get_mall_product( $mall_shop_id )
    {

        $spec = array();
        $include_service = array();
        $mall_shop_id = intval($mall_shop_id);

        $data = Shop::get_mall_product( $mall_shop_id );

        // foreach ($data as $row) 
        // {

        //   $spec[$row->id][] = array(
        //                         "id"        => $row->spec_id,
        //                         "cost"      => $row->cost,
        //                         "date_spec" => $row->date_spec
        //                       );

        // }


        // 關聯服務

        $mall_rel = mall_logic::get_mall_service_rel( array($mall_shop_id) );

        foreach ($mall_rel[$mall_shop_id] as $shop_id => $include_data) 
        {

          $include_service[$shop_id] = $include_data;
        
        }

        foreach ($data as $row) 
        {

          $result = array(
                          "mall_shop_id"           => $row->id,
                          "mall_product_name"         => $row->product_name,
                          "mall_product_description"  => $row->description,
                          // "mall_product_pic"          => $row->pic,
                          // "mall_rel"                  => $include_service,
                          // "mall_product_spec"         => $spec[$row->id],
                          "cost"                      => $row->cost,
                          "include_service"           => $include_service,
                        );

        }

        return $result;

    }

    public static function order_format( $data )
    {

        $Login_user = Session::get('Login_user');

        $result = array(
                      "mall_shop_id"          => $data['mall_shop_id'],
                      // "mall_shop_spec_id"     => $data->id,
                      "user_id"               => $Login_user["user_id"],
                      "cost"                  => $data["cost"],
                      "number"                => $data['buy_number'],
                      "total"                 => (int)$data['buy_number'] * (int)$data["cost"],
                      "status"                => 1, // 假設付清
                      "paid_at"               => date("Y-m-d H:i:s"),
                      "created_at"            => date("Y-m-d H:i:s"),
                      "updated_at"            => date("Y-m-d H:i:s")
                  );

        return $result;

    }

    public static function use_data_format( $data, $mall_product )
    {

        $Login_user = Session::get('Login_user');

        $result = array();

        foreach ($mall_product["include_service"] as $mall_product_id => $row) 
        {

            $cnt = $mall_product["buy_number"] * $row["number"];

            for ($i=0; $i < $cnt; $i++) 
            { 

                $result[] = array(
                              "user_id"               => $Login_user["user_id"],
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

        return $result;

    }

    public static function shop_buy_insert( $data )
    {

      $_this = new self();

      $txt = Web_cht::get_txt();

      $data = array(
                    "mall_product_number"   => isset($data["mall_product_number"]) ? intval($data["mall_product_number"]) : 0,
                    "mall_shop_id"          => isset($data["mall_shop_id"]) ? intval($data["mall_shop_id"]) : 0
                );

      $mall_product = $_this->get_mall_product( $data["mall_shop_id"] );

      $mall_product["buy_number"] = $data["mall_product_number"];

      // 主資料格式

      $insert_record = $_this->order_format( $mall_product );

      // INSERT 主資料

      $record_id = Shop::shop_buy_insert( $insert_record );

      $insert_record["record_id"] = $record_id;

      $use_data = $_this->use_data_format( $insert_record, $mall_product );

      $use_record_id = Shop::add_use_record( $use_data );

      $result = array(
                  "subject" => $record_id && $use_record_id ? $txt["buy_finish_title_success"] : $txt["buy_finish_title_fail"] ,
                  "content" => $record_id && $use_record_id ? $txt["buy_finish_content_success"] : $txt["buy_finish_content_fail"]
                );

      return $result;

    }

    public static function get_product_spec( $spec_id )
    {

        return Shop::get_product_spec( $spec_id );

    }


    // public static function shop_buy_insert( $data )
    // {

        // $_this = new self();

        // $txt = Web_cht::get_txt();

        // INSERT 主資料

        // $record_id = Shop::shop_buy_insert( $data );

        // INSERT 使用資料

        // $data["record_id"] = $record_id;

        // $use_data = $_this->use_data_format( $data );

        // dd($use_data);

        // $result = array(
        //             "subject" => $record_id ? $txt["buy_finish_title_success"] : $txt["buy_finish_title_fail"] ,
        //             "content" => $record_id ? $txt["buy_finish_content_success"] : $txt["buy_finish_content_fail"]
        //           );

        // return $result;

    // }

    public static function get_mall_shop_id( $action_key )
    {

        $Login_user = Session::get('Login_user');

        $data = array(
                         "user_id"        => intval($Login_user["user_id"]),
                         "action_key"     => $action_key,
                   );

        return Shop::get_mall_shop_id( $data );

    }

    // 檢查使用紀錄的正確性(防止啟用到非自己所有的服務)

    public static function check_legal( $user_id, $data )
    {

      return Shop::check_legal( $user_id, $data );

    }

    // 加入使用紀錄

    public static function add_use_record( $item_id, $data, $type )
    {

        $_this = new self();

        $data = explode("-", $data);

        $txt = Web_cht::get_txt();

        $Login_user = Session::get('Login_user');

        $use_id = $_this->check_legal( $Login_user["user_id"], $data );

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

          return $result;

        }

    }

    // 取得已使用的購買品項

    public static function get_mall_product_use_record( $user_id, $item_id, $action_key )
    {

        $_this = new self();

        $result = array();

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

        $valuable_id = $_this->get_valuable_id( $user_id, $type );

        $already_add_free_deadline = array();

        $data = Shop::get_mall_product_use_record( $user_id, $item_id, $action_key, $type );

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

        return $result;

    }

    // 計算指定項目的截止日期(帳號、店鋪)

    public static function count_deadline( $item_id, $action_key )
    {

        $_this = new self();

        $result = array();

        $Login_user = Session::get('Login_user');

        $data = $_this->get_mall_product_use_record( $Login_user["user_id"], $item_id, $action_key );

        if ( !empty($data) && $data->count() > 0 ) 
        {

          foreach ($data as $row) 
          {

              $result[$row->active_item_id] = isset($result[$row->active_item_id]) ? intval($result[$row->active_item_id]) : 0 ;
              $result[$row->active_item_id]+= intval($row->date_spec);
          
              if ( $row->active_item_id == 1 ) 
              {

                $result[$row->active_item_id] = 9999;

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

        $Login_user = Session::get('Login_user');

        // 未使用的擴展數

        $use_cnt = $_this->get_not_use_extend( $Login_user["user_id"], "extend_deadline" );

        foreach ($use_cnt as $row) 
        {
          
            $key = $_this->made_key(array($row->mall_shop_id, $row->mall_product_id, $row->date_spec));

            $use_status[$key] = isset($use_status[$key]) ? $use_status[$key] : 0 ;

            $use_status[$key]++;

        }

        // 已購買的擴展數

        // $buy_cnt = $_this->get_count_by_action_key( "extend_deadline" );

        // foreach ($buy_cnt as $index => $row) 
        // {

        //     $use_status[$index] = isset($use_status[$index]) ? $use_status[$index] : 0 ;

        //     $use_status[$index]+= $row["count"] ;
        
        // }

        $buy_spec_data = array();

        if ( !empty($use_status) ) 
        {

            foreach ($use_status as $index => $row) 
            {

              $tmp = explode("-", $index);

              $buy_spec_data[$index] = $tmp[2].$txt["day_unit"];

              // foreach ($spec_data["data"] as $index => $row1) 
              // {

              //   if ( $use_status[$index] > 0 ) 
              //   {

              //     $buy_spec_data[$index] = $row1.$txt["day_unit"];
                
              //   }

              // }

            }

            $result = $buy_spec_data;

        }

        return $result;

    }

    // 取得已購買的品項

    public static function get_data_by_action_key( $action_key )
    {

        $Login_user = Session::get('Login_user');

        return Shop::get_shop_record_by_id( $Login_user["user_id"], $action_key );

    }

    // 過濾重複規格

    public static function get_date_spec_unique_array( $data )
    {

        $_this = new self();

        $result = array();

        $key = $_this->made_key( array($data->mall_shop_id, $data->mall_product_id, $data->date_spec) );

        $result[$key] = $data->date_spec;

        $result = !empty($result) ? array_filter($result, "intval") : $result ;

        return $result;

    }

    // 計算指定服務已購買的個數

    public static function get_count_by_action_key( $action_key )
    {

        $_this = new self();

        $result = array();

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

    protected function get_valuable_id( $user_id, $type )
    {

        $result = array();

        $data = Shop::get_valuable_id( $user_id, $type );

        if ( $data->count() > 0 ) 
        {

          foreach ($data as $row) 
          {

              $result[] = $row->active_item_id;

          }

        }

        return $result;

    }

    protected function add_date_spec_attribute( $data )
    {

        $_this = new self();

        $result = array();

        $date_spec = $_this->get_mall_product_rel_data();

        if ( $data->count() > 0 ) 
        {

          foreach ($data as &$row) 
          {

            $new_key = $_this->made_key( array($row->mall_shop_id, $row->mall_product_id) );

            $row->date_spec = isset($date_spec[$new_key]) ? $date_spec[$new_key] : 0 ;

          }

          $result = $data;

        }

        return $result;

    }

    // 從關聯表建立日期規格陣列

    protected function get_not_use_extend( $user_id, $action_key )
    {

        $_this = new self();

        $result = array();

        $data = Shop::get_not_use_extend( $user_id, $action_key );

        if ( $data->count() > 0 ) 
        {

          // 加入日期規格

          $data = $_this->add_date_spec_attribute( $data );

          $result = $data;

        }

        return $result;

    }

    // 返回deadline

    public static function get_deadline( $data, $extend_deadline_record )
    {

        $_this = new self();

        $result = "";

        if ( !empty($data) ) 
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

}
