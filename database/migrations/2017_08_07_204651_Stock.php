<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Stock extends Migration
{

    protected $user_table = "user";
    protected $role_table = "role";
    protected $service_table = "service";
    protected $user_role_table = "user_role_relation";
    protected $role_service_table = "role_service_relation";
    protected $service_user_table = "service_user_relation";
    protected $product_table = "product";
    protected $purchase_table = "purchase";
    protected $option_table = "option";
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

        Schema::create($this->stock_table, function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('shop_id')->default(0);
            $table->integer('purchase_id')->unsigned();
            $table->integer('stock')->default(0);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::table($this->stock_table, function($table) {
           $table->foreign('purchase_id')->references('id')->on($this->purchase_table);
        });

        // service
        $parents_id = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '庫存管理',
                'link'          => '',
                'parents_id'    => 0,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 31,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '庫存總量列表',
                'link'          => '/stock_total_list',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 32,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '庫存批量列表',
                'link'          => '/stock_batch_list',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 33,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '到期庫存列表',
                'link'          => '/immediate_stock_list',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 34,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '安全庫存警示列表',
                'link'          => '/lack_of_stock_list',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 35,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

         // role_service
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%庫存%')->get();
        foreach ($service_data as $row) 
        {
            DB::table($this->role_service_table)->insert(
                array(
                    'role_id'      => 1,
                    'service_id'   => $row->id
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

        // role_service
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%庫存%')->get();
        foreach ($service_data as $row) 
        {
            DB::table($this->role_service_table)->where("service_id" ,"=", $row->id)->delete();
        }

        foreach ($service_data as $row) 
        {
            DB::table($this->service_table)->where("id" ,"=", $row->id)->delete();
        }

        Schema::dropIfExists($this->stock_table);
    
    }
}
