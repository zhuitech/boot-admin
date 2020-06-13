<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class AddMobileToAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->string('mobile')->comment('手机号码')->after('name')->unique()->nullable();
            $table->string('email')->comment('邮箱地址')->after('username')->unique()->nullable();
	        $table->integer('status')->comment('状态')->default(1);
	        $table->comment = '后台用户表';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'email', 'status']);
        });
    }
}
