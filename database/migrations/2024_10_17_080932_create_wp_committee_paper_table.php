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
        Schema::create('wp_committee_paper', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('wp_business_info_id');
            $table->integer('wp_loan_info_id');
            $table->string('business_name', 100)->nullable();
            $table->string('introduction', 100)->nullable();
            $table->string('company_number', 100)->nullable();
            $table->string('amount_funding', 100)->nullable();
            $table->string('registered_address', 100)->nullable();
            $table->string('servicing_and_administration_fee', 100)->nullable();
            $table->string('date_of_incorporation', 100)->nullable();
            $table->string('multiple', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('total_repayable', 100)->nullable();
            $table->string('shareholding_and_directors', 100)->nullable();
            $table->string('allocation_of_the_funds', 100)->nullable();
            $table->string('personal_guarantees', 100)->nullable();
            $table->string('pre_introducer_irr', 100)->nullable();
            $table->string('post_introducer_irr', 100)->nullable();
            $table->string('how_they_make_their_money', 100)->nullable();
            $table->string('why_irr_chosen', 100)->nullable();
            $table->string('duration', 100)->nullable();
            $table->string('how_long_until_breakeven', 100)->nullable();
            $table->string('rate_of_income', 100)->nullable();
            $table->string('creditsafe_status', 100)->nullable();
            $table->string('repayment_frequency', 100)->nullable();
            $table->string('active_ccjs', 100)->nullable();
            $table->string('repayment_type', 100)->nullable();
            $table->string('weighted_scorecard', 100)->nullable();
            $table->string('fixed_repayment_amount', 100)->nullable();
            $table->integer('ga_credit_safe_id')->nullable();
            $table->foreign('wp_business_info_id', 'wp_business_info_id_fk')
                ->references('id')
                ->on('wp_business_info')
                ->onDelete('cascade');
            $table->foreign('wp_loan_info_id', 'wp_loan_info_id_fk')
                ->references('id')
                ->on('wp_loan_info')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_committee_paper');
    }
};
