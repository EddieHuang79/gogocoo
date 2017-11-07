<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EDM extends Migration
{

    protected $service_table = "service";
    protected $user_role_table = "user_role_relation";
    protected $role_service_table = "role_service_relation";
    protected $edm_table = "edm";
    protected $edm_list_table = "edm_list";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // type 1: text, 2: image

        // status 1: 草稿, 2: 待發送, 3: 已發送, 4: 取消

        Schema::create($this->edm_table, function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('type')->default(1)->index();
            $table->string('subject');
            $table->string('content');
            $table->dateTime('send_time');
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        // Schema::create($this->edm_list_table, function (Blueprint $table) {
        //     $table->increments('id')->unique();
        //     $table->integer('edm_id')->unsigned();
        //     $table->string('email');
        //     $table->engine = 'InnoDB';
        // });      

        // Schema::table($this->edm_list_table, function($table) {
        //    $table->foreign('edm_id')->references('id')->on($this->edm_table);
        // });


        // service
        $parents_id = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '電子報管理',
                'link'          => '',
                'parents_id'    => 0,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 47,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '新增電子報',
                'link'          => '/edm/create',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 48,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '電子報列表',
                'link'          => '/edm',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 49,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '電子報審核',
                'link'          => '/edm_verify_list',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 50,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '電子報取消',
                'link'          => '/edm_cancel_list',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 50,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );


         // role_service
        $service_data = DB::table('service')->select('id')->whereIn('name', array('電子報管理','新增電子報','電子報列表','電子報審核','電子報取消'))->get();
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

        Storage::deleteDirectory("edm_list");

        Storage::deleteDirectory("edm_image");
        
        // Schema::dropIfExists($this->edm_list_table);
        
        Schema::dropIfExists($this->edm_table);

        // role_service
        $service_data = DB::table('service')->select('id')->whereIn('name', array('電子報管理','新增電子報','電子報列表','電子報審核','電子報取消'))->get();
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
