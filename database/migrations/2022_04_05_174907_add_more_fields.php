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
        Schema::table('codes', function (Blueprint $table) {
           $table->string('template_id')->nullable();
           $table->string('client_id')->nullable();
           $table->string('client_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
             $table->dropColumn('template_id');
             $table->dropColumn('client_id');
             $table->dropColumn('client_secret');
        });
    }
};
