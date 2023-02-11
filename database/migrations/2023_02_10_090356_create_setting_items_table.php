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
        Schema::create('setting_items', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('offset')->default(0);
            $table->time('time_start')->default('00:00');
            $table->time('time_end')->default('00:00');
            $table->foreignId('setting_id');
            $table->foreign('setting_id')->references('id')->on('settings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_items', function (Blueprint $table) {
            $table->dropForeign('setting_items_setting_id_foreign');
        });
        Schema::dropIfExists('setting_items');
    }
};
