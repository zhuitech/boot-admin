<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class AddBlankToAdminMenuTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('admin_menu', function (Blueprint $table) {
            $table->integer('blank')->comment('弹出新窗口')->after('icon')->default(0);
	        $table->string('icon')->comment('图标')->nullable()->change();
	        $table->comment = '后台菜单表';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('admin_menu', function (Blueprint $table) {
            $table->dropColumn(['blank']);
        });
    }
}
