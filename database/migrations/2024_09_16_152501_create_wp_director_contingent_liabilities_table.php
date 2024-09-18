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
        Schema::create('wp_director_contingent_liabilities', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('wp_director_info_id');
            $table->string('creditor', 100)->nullable();
            $table->string('nature_of_pg', 100)->nullable();
            $table->string('unlimited_guarantee_or_limit_value', 100)->nullable();
            $table->foreign('wp_director_info_id', 'wp_director_contingent_lib_fk')
                  ->references('id')
                  ->on('wp_director_info')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_director_contingent_liabilities');
    }
};
