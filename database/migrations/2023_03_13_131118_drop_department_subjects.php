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
        Schema::disableForeignKeyConstraints();
        Schema::table('department_subjects', function (Blueprint $table) {
            $table->dropForeign('department_subjects_department_id_foreign');
        });
        Schema::dropIfExists('department_subjects');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('department_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }
};
