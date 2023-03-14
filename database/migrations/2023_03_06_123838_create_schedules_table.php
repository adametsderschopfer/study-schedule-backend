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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id');
            $table->foreignId('department_id')->nullable();
            $table->foreignId('schedule_setting_id')->nullable();
            $table->foreignId('department_subject_id')->nullable();
            $table->foreignId('department_group_id')->nullable();
            $table->foreignId('teacher_id')->nullable();
            $table->smallInteger('shedule_setting_item_order')->default(0);
            $table->smallInteger('day_of_week')->default(0);
            $table->smallInteger('repeatability')->default(0)->index();
            $table->smallInteger('type')->nullable();
            $table->smallInteger('sub_group')->nullable();
            $table->dateTime('repeat_start')->nullable();
            $table->dateTime('repeat_end')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('schedule_setting_id')->references('id')->on('schedule_settings')->onDelete('cascade');
            $table->foreign('department_subject_id')->references('id')->on('department_subjects')->onDelete('cascade');
            $table->foreign('department_group_id')->references('id')->on('department_groups')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_account_id_foreign');
            $table->dropForeign('schedules_department_id_foreign');
            $table->dropForeign('schedules_schedule_setting_id_foreign');
            $table->dropForeign('schedules_department_subject_id_foreign');
            $table->dropForeign('schedules_department_group_id_foreign');
            $table->dropForeign('schedules_teacher_id_foreign');
        });
        Schema::dropIfExists('schedules');
    }
};
