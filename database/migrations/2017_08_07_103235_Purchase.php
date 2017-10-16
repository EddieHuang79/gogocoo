<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Purchase extends Migration
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

        Schema::create($this->purchase_table, function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('shop_id')->default(0);
            $table->integer('product_id')->unsigned();
            $table->integer('spec_id')->default(0);
            $table->integer('in_warehouse_number');
            $table->integer('number')->default(0);
            $table->date('in_warehouse_date');
            $table->date('deadline');
            $table->integer('category')->default(1);
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::table($this->purchase_table, function($table) {
           $table->foreign('product_id')->references('id')->on($this->product_table);
        });


        // 附屬資料
        // status: 1-new, 2-verify
        // category: 1-buy, 2-return

        Schema::create($this->purchase_extra_table, function (Blueprint $table) {
            $table->increments('extra_id')->unique();
            $table->integer('purchase_id')->unsigned();
            $table->integer('warehouse_id')->default(1);
            $table->engine = 'InnoDB';
        });

        Schema::table($this->purchase_extra_table, function($table) {
           $table->foreign('purchase_id')->references('id')->on($this->purchase_table);
        });

        $sub_admin_service_id = array();

        // service
        $purchase_id = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '進貨管理',
                'link'          => '',
                'parents_id'    => 0,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 28,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

        $sub_admin_service_id[] = $purchase_id;

        $sub_admin_service_id[] = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '新增進貨',
                'link'          => '/purchase/create',
                'parents_id'    => $purchase_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 29,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

        $sub_admin_service_id[] = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '進貨列表',
                'link'          => '/purchase',
                'parents_id'    => $purchase_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 30,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

         // role_service
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%進貨%')->get();
        
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

        }

        $in_warehouse_category = array(
                            1   =>  "採購",
                            2   =>  "退貨"
                    );

        DB::table($this->option_table)->insert(
            array(
                'type'          => 3,
                'key'           => "_in_warehouse_category_option",
                'value'         => json_encode($in_warehouse_category),
                'parents_id'    => 0,
                'status'        => 1,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

        $in_warehouse_category = array(
                            1   =>  "自家倉庫"
                    );

        DB::table($this->option_table)->insert(
            array(
                'type'          => 3,
                'key'           => "_warehouse_id",
                'value'         => json_encode($in_warehouse_category),
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
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%進貨%')->get();
        foreach ($service_data as $row) 
        {
            DB::table($this->role_service_table)->where("service_id" ,"=", $row->id)->delete();
        }

        foreach ($service_data as $row) 
        {
            DB::table($this->service_table)->where("id" ,"=", $row->id)->delete();
        }

        Schema::dropIfExists($this->purchase_extra_table);
        Schema::dropIfExists($this->purchase_table);

        DB::table($this->option_table)->whereIn("key" , array("_in_warehouse_category_option", "_warehouse_id"))->delete();


    }
}
