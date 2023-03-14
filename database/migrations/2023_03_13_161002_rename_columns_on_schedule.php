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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_department_subject_id_foreign');
            $table->renameColumn('department_subject_id', 'subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            $table->dropForeign('schedules_department_group_id_foreign');
            $table->renameColumn('department_group_id', 'group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_subject_id_foreign');
            $table->renameColumn('subject_id', 'department_subject_id');
            $table->foreign('department_subject_id')->references('id')->on('department_subjects')->onDelete('cascade');

            $table->dropForeign('schedules_group_id_foreign');
            $table->renameColumn('group_id', 'department_group_id');
            $table->foreign('department_group_id')->references('id')->on('department_groups')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }
};
