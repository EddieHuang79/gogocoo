<?php

namespace App\logic;

use App\model\Shop;
use Illuminate\Support\Facades\Session;
use App\logic\Web_cht;

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

    public static function get_mall_product( $mall_product_id )
    {

        $spec = array();

        $mall_product_id = intval($mall_product_id);

        $data = Shop::get_mall_product( $mall_product_id );

        foreach ($data as $row) 
        {
          $spec[$row->id][] = array(
                                "id"        => $row->spec_id,
                                "cost"      => $row->cost,
                                "date_spec" => $row->date_spec
                              );
        }

        foreach ($data as $row) 
        {

          $result = array(
                          "mall_product_id"           => $row->id,
                          "mall_product_name"         => $row->product_name,
                          "mall_product_description"  => $row->description,
                          "mall_product_pic"          => $row->pic,
                          "mall_product_spec"         => $spec[$row->id],
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
                      "mall_product_id"       => $data->mall_product_id,
                      "mall_product_spec_id"  => $data->id,
                      "deadline"              => date("Y-m-d H:i:s", mktime(23,59,59,date("m")+$data->date_spec,date("d"),date("Y"))),
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

}
