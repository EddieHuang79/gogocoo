<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Mall extends Migration
{

    protected $user_table = "user";
    protected $store_table = "store";
    protected $service_table = "service";
    protected $role_service_table = "role_service_relation";
    protected $mall_product_table = "mall_product";
    protected $mall_shop_spec_table = "mall_shop_spec";
    protected $mall_record_table = "mall_record";
    protected $mall_shop_table = "mall_shop";
    protected $mall_product_rel_table = "mall_product_rel";
    protected $mall_product_use_table = "mall_product_use";
    protected $mall_pay_record_table = "mall_pay_record";
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        // 商品清單

        #   date_spec - unit: month

        Schema::create($this->mall_shop_table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name');
            $table->text('description');
            $table->string('pic');
            $table->integer('public');
            $table->integer('cost');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });   

        // // 商品規格

        // #   date_spec - unit: month

        // Schema::create($this->mall_shop_spec_table, function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('mall_shop_id')->unsigned();
        //     $table->integer('cost');
        //     $table->integer('date_spec');
        //     $table->engine = 'InnoDB';
        // });    

        // Schema::table($this->mall_shop_spec_table, function($table) {
        //    $table->foreign('mall_shop_id')->references('id')->on($this->mall_shop_table);
        // });

        // 購買紀錄

        #   status - 0:未付,1:付,2:退

        Schema::create($this->mall_record_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mall_shop_id')->unsigned();
            // $table->integer('mall_shop_spec_id')->unsigned();
            $table->integer('MerchantTradeNo')->default(0);
            $table->integer('store_id')->unsigned();
            $table->integer('cost');
            $table->integer('number');
            $table->integer('total');
            $table->integer('status');
            $table->dateTime('paid_at');
            $table->string('mac');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });    

        Schema::table($this->mall_record_table, function($table) {
           $table->foreign('mall_shop_id')->references('id')->on($this->mall_shop_table);
           // $table->foreign('mall_shop_spec_id')->references('id')->on($this->mall_shop_spec_table);
           $table->foreign('store_id')->references('id')->on($this->store_table);
        });


        // 商城管理

        #   public - 1:yes,2:no

        Schema::create($this->mall_product_table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name');
            $table->text('description');
            $table->string('action_key');
            $table->engine = 'InnoDB';
        });    

        // insert product

        DB::table($this->mall_product_table)->insert(
            array(
                'product_name'  => '購買店舖',
                'description'   => '購買店舖',
                'action_key'    => 'create_shop'
            )
        );

        DB::table($this->mall_product_table)->insert(
            array(
                'product_name'  => '延長展期',
                'description'   => '延長展期',
                'action_key'    => 'extend_deadline'
            )
        );

        DB::table($this->mall_product_table)->insert(
            array(
                'product_name'  => '子帳數目擴充',
                'description'   => '子帳數目擴充',
                'action_key'    => 'child_account'
            )
        );

        DB::table($this->mall_product_table)->insert(
            array(
                'product_name'  => '簡訊通知',
                'description'   => '簡訊通知 - 安全庫存',
                'action_key'    => 'safe_amount_sms_notice'
            )
        );

        DB::table($this->mall_product_table)->insert(
            array(
                'product_name'  => '數據分析',
                'description'   => '數據分析',
                'action_key'    => 'data_anlaysis'
            )
        );

        DB::table($this->mall_product_table)->insert(
            array(
                'product_name'  => '進階報表',
                'description'   => '進階報表',
                'action_key'    => 'advance_report'
            )
        );

        // 商品關聯

        #   public - 1:yes,2:no

        Schema::create($this->mall_product_rel_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mall_shop_id')->unsigned();
            $table->integer('mall_product_id')->unsigned();
            $table->integer('date_spec');
            $table->integer('number');
            $table->engine = 'InnoDB';
        });    

        Schema::table($this->mall_product_rel_table, function($table) {
           $table->foreign('mall_shop_id')->references('id')->on($this->mall_shop_table);
           $table->foreign('mall_product_id')->references('id')->on($this->mall_product_table);
        });


        // 商城商品使用紀錄

        // #   type - 1:account, 2:store

        Schema::create($this->mall_product_use_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned();
            $table->integer('mall_record_id')->unsigned();
            $table->integer('mall_shop_id')->unsigned();
            $table->integer('mall_product_id')->unsigned();
            $table->integer('type');
            $table->integer('active_item_id');
            // $table->integer('number');
            // $table->date('deadline');
            $table->integer('status');
            $table->dateTime('use_time');
            $table->engine = 'InnoDB';
        });    

        Schema::table($this->mall_product_use_table, function($table) {
           $table->foreign('store_id')->references('id')->on($this->store_table);
           $table->foreign('mall_record_id')->references('id')->on($this->mall_record_table);
           $table->foreign('mall_shop_id')->references('id')->on($this->mall_shop_table);
           $table->foreign('mall_product_id')->references('id')->on($this->mall_product_table);
        });


        // 綠界回傳交易結果

        Schema::create($this->mall_pay_record_table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('MerchantTradeNo');
            $table->integer('RtnCode');
            $table->string('RtnMsg');
            $table->string('TradeNo');
            $table->integer('TradeAmt');
            $table->dateTime('PaymentDate');
            $table->string('PaymentType');
            $table->integer('PaymentTypeChargeFee');
            $table->dateTime('TradeDate');
            $table->string('CheckMacValue');
            $table->string('sysMsg');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });   

        // service

        $service_id = array();

        $service_id[] = DB::table($this->service_table)->insertGetId(
                                array(
                                    'name'          => '商城管理',
                                    'link'          => '',
                                    'parents_id'    => 0,
                                    'status'        => 1,
                                    'public'        => 4,
                                    'sort'          => 22,
                                    'created_at'    => date("Y-m-d H:i:s"),
                                    'updated_at'    => date("Y-m-d H:i:s")
                                )
                            );

        $service_id[] = DB::table($this->service_table)->insertGetId(
                                array(
                                    'name'          => '建立產品',
                                    'link'          => '/mall/create',
                                    'parents_id'    => $service_id[0],
                                    'status'        => 1,
                                    'public'        => 4,
                                    'sort'          => 23,
                                    'created_at'    => date("Y-m-d H:i:s"),
                                    'updated_at'    => date("Y-m-d H:i:s")
                                )
                            );

        $service_id[] = DB::table($this->service_table)->insertGetId(
                                array(
                                    'name'          => '產品列表',
                                    'link'          => '/mall',
                                    'parents_id'    => $service_id[0],
                                    'status'        => 1,
                                    'public'        => 4,
                                    'sort'          => 24,
                                    'created_at'    => date("Y-m-d H:i:s"),
                                    'updated_at'    => date("Y-m-d H:i:s")
                                )
                            );

        // role_service


        foreach ($service_id as $row) 
        {

            DB::table($this->role_service_table)->insert(
                array(
                    'role_id'      => 1,
                    'service_id'   => $row
                )
            );

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists($this->mall_pay_record_table);
        Schema::dropIfExists($this->mall_product_use_table);
        Schema::dropIfExists($this->mall_product_rel_table);
        Schema::dropIfExists($this->mall_product_table);
        Schema::dropIfExists($this->mall_record_table);
        // Schema::dropIfExists($this->mall_shop_spec_table);
        Schema::dropIfExists($this->mall_shop_table);

        // role_service
        $service_data = DB::table('service')->select('id')->whereIn('name', array('商城管理','建立產品','產品列表'))->get();
        foreach ($service_data as $row) 
        {
            DB::table($this->role_service_table)->where("service_id" ,"=", $row->id)->delete();
        }

        foreach ($service_data as $row) 
        {
            DB::table($this->service_table)->where("id" ,"=", $row->id)->delete();
        }

    }
}
