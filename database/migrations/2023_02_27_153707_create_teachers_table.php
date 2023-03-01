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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id');
            $table->string('full_name')->index();
            $table->string('position')->nullable();
            $table->string('degree')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::create('teacherables', function (Blueprint $table)  {
            $table->integer("teacher_id");
            $table->integer("teacherable_id");
            $table->string("teacherable_type");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign('teachers_account_id_foreign');
        });
        Schema::dropIfExists('teacherables');
        Schema::dropIfExists('teachers');
    }
};
