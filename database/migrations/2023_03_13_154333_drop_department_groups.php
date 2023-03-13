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
        Schema::table('department_groups', function (Blueprint $table) {
            $table->dropForeign('department_groups_department_id_foreign');
        });
        Schema::dropIfExists('department_groups');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('department_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('name')->nullable();
            $table->mediumInteger('sub_group')->default(0);
            $table->smallInteger('degree')->default(0)->index();
            $table->smallInteger('year_of_education')->default(0)->index();
            $table->smallInteger('form_of_education')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }
};
