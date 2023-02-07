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
        Schema::create('modes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_external_id');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->foreign('account_external_id')->references('external_id')->on('accounts')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modes', function (Blueprint $table) {
            $table->dropForeign('modes_account_external_id_foreign');
        });
        Schema::dropIfExists('modes');
    }
};
