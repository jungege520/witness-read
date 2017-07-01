<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVueMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vue_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->default(0)->comment('状态 0展示菜单    1操作菜单');
            $table->integer('parent_id')->default(0)->comment('父级菜单ID');
            $table->string('name', 100)->default('')->comment('菜单名称');
            $table->string('icon', 500)->default('')->comment('菜单icon');
            $table->string('url', 500)->default('')->comment('前端路由跳转名称，没有跳转为#');
            $table->string('model', 500)->default('')->comment('权限名称，一般操作菜单menu.add中的add才有效果');
            $table->string('desc', 500)->default('')->comment('菜单描述信息');
            $table->tinyInteger('sort')->default(0)->comment('排序值越大越靠前');
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
        Schema::drop('vue_menu');
    }
}
