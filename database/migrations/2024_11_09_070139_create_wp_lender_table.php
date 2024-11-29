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
        Schema::create('wp_lender', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('wp_business_info_id');
            $table->string('lender_name')->nullable();
            $table->decimal('outstanding_amount', 10, 2)->nullable();
            $table->decimal('how_much_gets_paid', 10, 2)->nullable();
            $table->string('how_often_it_is_paid')->nullable();
            $table->string('expected_maturity')->nullable();
            $table->string('security_given')->nullable();
          
            $table->foreign('wp_business_info_id', 'wp_lender_business_info_fk')
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
        Schema::dropIfExists('wp_lender');
    }
};
