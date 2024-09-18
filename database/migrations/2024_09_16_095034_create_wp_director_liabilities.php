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
        Schema::create('wp_director_liabilities', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('wp_director_info_id');
            $table->string('account_or_regnumber', 100)->nullable();
            $table->string('personal_loans_and_overdrafts', 100)->nullable();
            $table->string('mortgages', 100)->nullable();
            $table->string('credit_card_debts', 100)->nullable();
            $table->string('motor_loan', 100)->nullable();
            $table->string('property_rental', 100)->nullable();
            $table->string('other_debt_and_contingent_liabilities', 100)->nullable();
            $table->string('other_liabilities', 100)->nullable();
            $table->foreign('wp_director_info_id', 'wp_director_liabilities_fk')
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
        Schema::dropIfExists('wp_director_liabilities');
    }
};
