<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_setting_items', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('offset')->default(0);
            $table->time('time_start')->default('00:00');
            $table->time('time_end')->default('00:00');
            $table->foreignId('schedule_setting_id');
            $table->foreign('schedule_setting_id')->references('id')->on('schedule_settings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_setting_items', function (Blueprint $table) {
            $table->dropForeign('schedule_setting_items_schedule_setting_id_foreign');
        });
        Schema::dropIfExists('schedule_setting_items');
    }
};
