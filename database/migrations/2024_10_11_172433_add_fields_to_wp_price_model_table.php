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
        Schema::table('wp_price_model', function (Blueprint $table) {
            $table->integer('arrangement_fee_excl_VAT')->nullable();
            $table->integer('arrangement_fee_incl_VAT')->nullable();
            $table->decimal('total_repayable', 10, 2)->nullable();
            $table->decimal('pre_introducer_IRR', 10, 2)->nullable();
            $table->decimal('post_introducer_IRR', 10, 2)->nullable();
            $table->integer('duration')->nullable();
            $table->integer('how_long_until_breakeven')->nullable();
            $table->decimal('rate_of_income', 10, 2)->nullable();
            $table->decimal('fixed_repayment_amount', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wp_price_model', function (Blueprint $table) {
             // Dropping the fields if needed
             $table->dropColumn('arrangement_fee_excl_VAT');
             $table->dropColumn('arrangement_fee_incl_VAT');
             $table->dropColumn('total_repayable');
             $table->dropColumn('pre_introducer_IRR');
             $table->dropColumn('post_introducer_IRR');
             $table->dropColumn('duration');
             $table->dropColumn('how_long_until_breakeven');
             $table->dropColumn('rate_of_income');
             $table->dropColumn('fixed_repayment_amount');
        });
    }
};
