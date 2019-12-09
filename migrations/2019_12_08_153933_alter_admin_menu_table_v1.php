<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdminMenuTableV1 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('admin_menu', function (Blueprint $table) {
            $table->string('icon')->comment('图标')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('admin_menu', function (Blueprint $table) {
        });
    }
}
