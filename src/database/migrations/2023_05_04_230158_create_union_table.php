<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('union', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->comment('用户ID');
            $table->string('nickname',32)->nullable()->comment('昵称');
            $table->string('headimgurl')->nullable()->comment('头像');
            $table->tinyInteger('sex')->nullable()->comment('性别');
            $table->string('phone',11)->nullable()->comment('手机号');
            $table->string('country',64)->nullable()->comment('国家');
            $table->string('language',64)->nullable()->comment('国家');
            $table->string('province',64)->nullable()->comment('省份');
            $table->string('city',128)->nullable()->comment('市区');
            $table->string('privilege')->nullable()->comment('用户特权信息');
            $table->string('unionid')->nullable()->comment('unionid');
            $table->string('openid')->nullable()->comment('openid');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('union');
    }
}
