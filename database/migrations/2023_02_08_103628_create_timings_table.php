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
        Schema::create('timings', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('offset')->default(0);
            $table->time('time_start')->default('00:00');
            $table->time('time_end')->default('00:00');
            $table->foreignId('mode_id');
            $table->foreign('mode_id')->references('id')->on('modes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timings', function (Blueprint $table) {
            $table->dropForeign('timings_mode_id_foreign');
        });
        Schema::dropIfExists('timings');
    }
};
