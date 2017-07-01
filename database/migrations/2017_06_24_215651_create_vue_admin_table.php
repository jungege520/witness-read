<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVueAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vue_admin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 20)->default('')->comment('管理员登录账号，一般为手机号码');
            $table->string('nickname', 100)->default('')->comment('昵称');
            $table->string('password', 500)->default('')->comment('密码');
            $table->integer('role')->default(0)->comment('权限角色ID');
            $table->string('icon', 255)->default('')->comment('管理员图标');
            $table->tinyInteger('status')->default(1)->comment('状态 -1已删除    1正常');
            $table->integer('login_time')->default(0)->comment('登录时间');
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
        Schema::drop('vue_admin');
    }
}
