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
            $table->string('mobile', 100)->comment('手机号码')->after('name')->nullable();
            $table->string('email', 100)->comment('邮箱地址')->after('username')->nullable();
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
