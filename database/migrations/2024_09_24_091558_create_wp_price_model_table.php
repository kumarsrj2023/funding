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
        Schema::create('wp_price_model', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('wp_business_info_id');
            $table->string('advance_requested', 100)->nullable();
            $table->string('repayment_period', 100)->nullable();
            $table->string('multiple', 100)->nullable();
            $table->string('property_equity', 100)->nullable();
            $table->string('average_monthly_revenue', 100)->nullable();
            $table->foreign('wp_business_info_id', 'wp_price_model_fk')
                ->references('id')
                ->on('wp_business_info')
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
        Schema::dropIfExists('wp_price_model');
    }
};
