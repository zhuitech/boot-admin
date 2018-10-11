<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEmailNullAdminTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->string('mobile')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
        });
    }
}
