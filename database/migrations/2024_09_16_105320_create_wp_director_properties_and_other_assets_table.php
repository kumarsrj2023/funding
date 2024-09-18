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
        Schema::create('wp_director_properties_and_other_assets', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('wp_director_info_id');
            $table->string('property_address_and_assets', 100)->nullable();
            $table->string('estimated_value', 100)->nullable();
            $table->string('debt', 100)->nullable();
            $table->string('financing_costs', 100)->nullable();
            $table->string('income', 100)->nullable();
            $table->foreign('wp_director_info_id', 'wp_dir_properties_and_other_fk')
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
        Schema::dropIfExists('wp_director_properties_and_other_assets');
    }
};
