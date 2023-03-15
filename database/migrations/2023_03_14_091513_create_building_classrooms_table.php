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
        Schema::create('building_classrooms', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->foreignId('building_id');
            $table->string('name');
            $table->timestamps();
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_classrooms', function (Blueprint $table) {
            $table->dropForeign('building_classrooms_building_id_foreign');
        });
        Schema::dropIfExists('building_classrooms');
    }
};
