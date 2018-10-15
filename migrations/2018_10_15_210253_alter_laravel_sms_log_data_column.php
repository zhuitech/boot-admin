<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLaravelSmsLogDataColumn extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('laravel_sms_log', function (Blueprint $table) {
            $table->string('data', 1024)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('laravel_sms_log', function (Blueprint $table) {
        });
    }
}
