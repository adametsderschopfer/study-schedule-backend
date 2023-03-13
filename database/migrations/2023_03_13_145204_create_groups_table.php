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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id');
            $table->string('name')->nullable();
            $table->mediumInteger('sub_group')->default(0);
            $table->smallInteger('degree')->default(0)->index();
            $table->smallInteger('year_of_education')->default(0)->index();
            $table->smallInteger('form_of_education')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::create('groupables', function (Blueprint $table)  {
            $table->integer("group_id");
            $table->integer("groupable_id");
            $table->string("groupable_type");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign('groups_account_id_foreign');
        });
        Schema::dropIfExists('groupables');
        Schema::dropIfExists('groups');
    }
};
