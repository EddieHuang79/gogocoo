<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Order extends Migration
{

    protected $user_table = "user";
    protected $role_table = "role";
    protected $service_table = "service";
    protected $user_role_table = "user_role_relation";
    protected $role_service_table = "role_service_relation";
    protected $service_user_table = "service_user_relation";
    protected $product_table = "product";
    protected $purchase_table = "purchase";
    protected $purchase_extra_table = "purchase_extra";
    protected $option_table = "option";
    protected $order_table = "order";
    protected $order_extra_table = "order_extra";
    protected $order_stock_record_table = "order_stock_record";
    protected $stock_table = "stock";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // 主資料
        // status: 1-new, 2-verify
        // category: 1-buy, 2-return

        Schema::create($this->order_table, function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('shop_id')->default(0);
            $table->integer('product_id')->unsigned();
            $table->integer('spec_id')->default(0);
            $table->integer('order_number')->default(0);
            $table->integer('number')->default(0);
            $table->date('out_warehouse_date');
            $table->integer('category')->default(1);
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::table($this->order_table, function($table) {
           $table->foreign('product_id')->references('id')->on($this->product_table);
        });


        // 附屬資料
        // pay_status: 1-no, 2-pay
        // logistics_type:是否親取 1 - 親自拿, 2 - 走物流

        Schema::create($this->order_extra_table, function (Blueprint $table) {
            $table->increments('extra_id')->unique();
            $table->integer('order_id')->unsigned();
            $table->integer('logistics_type')->default(1);
            $table->string('ori_order_number')->default('');        // 訂單編號
            $table->date('ori_order_date')->default(date("Y-m-d"));            // 訂單日期
            $table->string('ori_order_status')->default(1);       // 訂單狀態
            $table->string('ori_custom_name')->default('');         // 顧客姓名
            $table->string('ori_custom_email')->default('');        // email
            $table->string('ori_custom_phone')->default('');        // 顧客電話
            $table->string('ori_custom_sex')->default(1);         // 性別
            $table->date('ori_custom_birthday')->default(date("Y-m-d"));       // 生日
            $table->text('remark')->default('');                    // 備註
            $table->string('send_type')->default(1);              // 送貨方式
            $table->string('send_status')->default(1);            // 送貨狀態
            $table->string('pay_type')->default(1);               // 付款方式
            $table->string('pay_status')->default(1);             // 付款狀態
            $table->integer('sub_total')->default(0);              // 小計
            $table->integer('post_fee')->default(0);               // 運費
            $table->integer('attr_fee')->default(0);               // 附加費
            $table->integer('discount_fee')->default(0);               // 優惠
            $table->integer('total')->default(0);                  // 合計
            $table->string('trade_code')->default('');              // 交易編號
            $table->date('pay_date')->default(date("Y-m-d"));                  // 付款日期
            $table->string('currency')->default('');                // 幣別
            $table->string('admin_remark')->default('');            // 管理員備註
            $table->string('receiver')->default('');                // 收件人
            $table->string('receiver_phone')->default('');          // 收件人電話
            $table->string('receiver_address1')->default('');       // 收件人地址1
            $table->string('receiver_address2')->default('');       // 收件人地址2
            $table->string('receiver_city')->default('');           // 收件人城市
            $table->string('receiver_section')->default('');        // 收件人區域
            $table->string('receiver_country')->default('');        // 收件人國家
            $table->string('post_code')->default('');               // 收件人郵遞區號
            $table->string('send_remark')->default('');             // 送貨備註
            $table->string('receiver_store_name')->default('');     // 收件門市
            $table->string('receiver_store_code')->default('');     // 收件店號
            $table->string('send_code')->default('');               // 送貨編號
            $table->string('receipt_number')->default('');          // 發票號碼
            $table->string('receipt_title')->default('');           // 發票抬頭
            $table->string('company_code')->default('');            // 公司統編
            $table->string('receipt_address')->default('');         // 發票地址
            $table->string('product_code')->default('');            // 產品編號
            $table->string('ori_product_name')->default('');        // 產品名稱
            $table->string('option')->default('');                  // 選項
            $table->integer('is_additional_product')->default(0);   // 是否為加購品
            $table->engine = 'InnoDB';
        });

        Schema::table($this->order_extra_table, function($table) {
           $table->foreign('order_id')->references('id')->on($this->order_table);
        });


        // 扣庫紀錄
        Schema::create($this->order_stock_record_table, function (Blueprint $table) {
            $table->increments('order_record_id')->unique();
            $table->integer('order_id')->unsigned();
            $table->integer('stock_id')->unsigned();
            $table->integer('cost_number')->default(0);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::table($this->order_stock_record_table, function($table) {
           $table->foreign('order_id')->references('id')->on($this->order_table);
           $table->foreign('stock_id')->references('id')->on($this->stock_table);
        });

        $sub_admin_service_id = array();

        // service
        $order_id = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '訂單管理',
                'link'          => '',
                'parents_id'    => 0,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 36,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

        $sub_admin_service_id[] = $order_id;
        
        $sub_admin_service_id[] = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '新增訂單',
                'link'          => '/order/create',
                'parents_id'    => $order_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 37,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        
        $sub_admin_service_id[] = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '訂單列表',
                'link'          => '/order',
                'parents_id'    => $order_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 38,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

        $sub_admin_service_id[] = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '訂單匯出',
                'link'          => '/order_output',
                'parents_id'    => $order_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 51,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

        // role_service
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%訂單%')->get();
        
        foreach ($service_data as $row) 
        {
            DB::table($this->role_service_table)->insert(
                array(
                    'role_id'      => 1,
                    'service_id'   => $row->id
                )
            );
        }

        foreach ($sub_admin_service_id as $row) 
        {

            DB::table($this->role_service_table)->insert(
                array(
                    'role_id'      => 2,
                    'service_id'   => $row
                )
            );

            DB::table($this->role_service_table)->insert(
                array(
                    'role_id'      => 3,
                    'service_id'   => $row
                )
            );

            DB::table($this->role_service_table)->insert(
                array(
                    'role_id'      => 4,
                    'service_id'   => $row
                )
            );
            
        }

        $out_warehouse_category = array(
                            1   =>  "銷售",
                            2   =>  "銷毀"
                    );

        DB::table($this->option_table)->insert(
            array(
                'type'          => 3,
                'key'           => "_out_warehouse_category_option",
                'value'         => json_encode($out_warehouse_category),
                'parents_id'    => 0,
                'status'        => 1,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        // role_service
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%訂單%')->get();
        foreach ($service_data as $row) 
        {
            DB::table($this->role_service_table)->where("service_id" ,"=", $row->id)->delete();
        }

        foreach ($service_data as $row) 
        {
            DB::table($this->service_table)->where("id" ,"=", $row->id)->delete();
        }

        Schema::dropIfExists($this->order_stock_record_table);
        Schema::dropIfExists($this->order_extra_table);
        Schema::dropIfExists($this->order_table);

        DB::table($this->option_table)->whereIn("key" , array("_out_warehouse_category_option"))->delete();

    }
}
