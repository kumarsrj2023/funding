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
        Schema::create('wp_director_household_income', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('wp_director_info_id');
            $table->string('type_and_source', 100)->nullable();
            $table->string('who_in_household', 100)->nullable();
            $table->string('gross_annual_income', 100)->nullable();
            $table->foreign('wp_director_info_id', 'wp_dir_household_income_fk')
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
        Schema::dropIfExists('wp_director_household_income');
    }
};
