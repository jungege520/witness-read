<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVueRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vue_role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->default('')->comment('权限名称');
            $table->string('desc', 500)->default('')->comment('权限描述信息');
            $table->text('role')->default('')->comment('权限内容，一般是存序列化号的数组');
            $table->tinyInteger('status')->default(1)->comment('状态 -1已删除    1正常');
            $table->integer('create_time')->default(0)->comment('创建时间');
            $table->integer('update_time')->default(0)->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vue_role');
    }
}
