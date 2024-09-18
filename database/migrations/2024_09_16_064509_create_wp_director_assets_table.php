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
        Schema::create('wp_director_assets', function (Blueprint $table) {
          // Primary key
          $table->bigIncrements('id');

          // Foreign key column
          $table->bigInteger('wp_director_info_id');

          // Other columns
          $table->string('account_or_regnumber', 100)->nullable();
          $table->string('cash_in_bank_and_deposit', 100)->nullable();
          $table->string('public_listed_shares', 100)->nullable();
          $table->tinyInteger('properties')->nullable();
          $table->string('motor_vehicles_boats', 100)->nullable();
          $table->string('other_cash_investments', 100)->nullable();
          $table->string('details_of_personal_pension', 100)->nullable();
          $table->string('other_assets', 100)->nullable();

          $table->foreign('wp_director_info_id','wp_director_assets_fk')
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
        Schema::dropIfExists('wp_director_assets');
    }
};
