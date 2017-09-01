<?php

namespace App\logic;

use App\model\Shop;
use Illuminate\Support\Facades\Session;
use App\logic\Web_cht;
use App\logic\mall_logic;

class Shop_logic extends Basetool
{

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

      $data = array(
                       "user_id"       => intval($Login_user["user_id"])
                 );

      return Shop::shop_record( $data );

   }

    public static function get_mall_product( $mall_shop_id )
    {

        $spec = array();
        $include_service = array();
        $mall_shop_id = intval($mall_shop_id);

        $data = Shop::get_mall_product( $mall_shop_id );

        foreach ($data as $row) 
        {

          $spec[$row->id][] = array(
                                "id"        => $row->spec_id,
                                "cost"      => $row->cost,
                                "date_spec" => $row->date_spec
                              );

        }


        // 關聯服務

        $mall_rel = mall_logic::get_mall_service_rel( array($mall_shop_id) );

        foreach ($mall_rel[$mall_shop_id] as $key => $value) 
        {

          $include_service[$key] = $value;
        
        }

        foreach ($data as $row) 
        {

          $result = array(
                          "mall_product_id"           => $row->id,
                          "mall_product_name"         => $row->product_name,
                          "mall_product_description"  => $row->description,
                          // "mall_product_pic"          => $row->pic,
                          "mall_rel"                  => $include_service,
                          "mall_product_spec"         => $spec[$row->id],
                          "include_service"           => $include_service,
                        );

        }

        return $result;

    }

    public static function shop_buy_format( $data )
    {

      $_this = new self();

      $result = array(
                    "mall_product_number"   => isset($data["mall_product_number"]) ? intval($data["mall_product_number"]) : 0,
                    "mall_product_spec_id"  => isset($data["mall_product_spec"]) ? intval($data["mall_product_spec"]) : 0
                );

      $mall_product_spec = $_this->get_product_spec( $result["mall_product_spec_id"] );

      $mall_product_spec->buy_number = $result["mall_product_number"];

      $result = $_this->order_format( $mall_product_spec );

      return $result;

    }

    public static function get_product_spec( $spec_id )
    {

        return Shop::get_product_spec( $spec_id );

    }

    public static function order_format( $data )
    {

        $Login_user = Session::get('Login_user');

        $result = array(
                      "mall_shop_id"          => $data->mall_shop_id,
                      "mall_shop_spec_id"     => $data->id,
                      "user_id"               => $Login_user["user_id"],
                      "cost"                  => $data->cost,
                      "number"                => $data->buy_number,
                      "total"                 => (int)$data->buy_number * (int)$data->cost,
                      "status"                => 1, // 假設付清
                      "paid_at"               => date("Y-m-d H:i:s"),
                      "created_at"            => date("Y-m-d H:i:s"),
                      "updated_at"            => date("Y-m-d H:i:s")
                  );

        return $result;

    }

    public static function shop_buy_insert( $data )
    {

        $txt = Web_cht::get_txt();

        $insert_result = Shop::shop_buy_insert( $data );

        $result = array(
                    "subject" => $insert_result ? $txt["buy_finish_title_success"] : $txt["buy_finish_content_fail"] ,
                    "content" => $insert_result ? $txt["buy_finish_content_success"] : $txt["buy_finish_content_fail"]
                  );

        return $result;

    }

    public static function get_shop_record_by_id( $action_key )
    {

        $result = 0;

        $Login_user = Session::get('Login_user');

        $data = array(
                         "user_id"        => intval($Login_user["user_id"])
                   );

        $data = Shop::get_shop_record_by_id( $data );

        foreach ($data as $row) 
        {

            $result+= $row->action_key == $action_key ? $row->number : 0 ;

        }

        return $result;

    }

    public static function get_mall_shop_id( $action_key )
    {

        $Login_user = Session::get('Login_user');

        $data = array(
                         "user_id"        => intval($Login_user["user_id"]),
                         "action_key"     => $action_key,
                   );

        return Shop::get_mall_shop_id( $data );

    }

    // public static function add_use_record( $action_key )
    // {

    //     $_this = new self();

    //     $data = $_this->get_mall_shop_id( $action_key );

    //     $Login_user = Session::get('Login_user');

    //     $data = array(
    //                      "user_id"        => intval($Login_user["user_id"]),
    //                      "mall_shop_id"   => intval($mall_shop_id),
    //                      "action_key"     => $action_key,
    //                      "use_time"       => date("Y-m-d H:i:s"),
    //                );

    //     Shop::add_use_record( $data );

    // }


}
