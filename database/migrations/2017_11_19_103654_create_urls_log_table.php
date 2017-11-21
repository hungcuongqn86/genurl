<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrlsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('url_log');
        Schema::create('url_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('url_id')->nullable();
            $table->string('ip',60)->nullable();
            $table->string('countryCode',60)->nullable();
            $table->string('referer',200)->nullable();
            $table->smallInteger('device_type')->nullable();
            $table->string('device_name',50)->nullable();
            $table->string('browser',50)->nullable();
            $table->string('platform',50)->nullable();
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
        //
        Schema::dropIfExists('url_log');
    }
}
