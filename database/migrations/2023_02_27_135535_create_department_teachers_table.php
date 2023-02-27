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
        Schema::create('department_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('full_name')->index();
            $table->string('position')->nullable();
            $table->string('degree')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('department_teachers', function (Blueprint $table) {
            $table->dropForeign('department_teachers_department_id_foreign');
        });
        Schema::dropIfExists('department_teachers');
    }
};
