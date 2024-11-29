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
        Schema::create('wp_committee_paper_director_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('wp_business_info_id');
            $table->integer('wp_loan_info_id');
            $table->unsignedBigInteger('wp_committee_paper_id');
            $table->string('full_name', 100)->nullable();
            $table->string('date_of_birth', 100)->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('cifas_return', 100)->nullable();
            $table->string('appointed_date', 100)->nullable();
            $table->string('transunion', 100)->nullable();
            $table->string('home_address', 100)->nullable();
            $table->integer('wp_director_info_id')->nullable();
    
            $table->foreign('wp_business_info_id')
                ->references('id')
                ->on('wp_business_info')
                ->onDelete('cascade');
            $table->foreign('wp_loan_info_id')
                ->references('id')
                ->on('wp_loan_info')
                ->onDelete('cascade');
            // $table->foreign('wp_committee_paper_id')
            //     ->references('id')
            //     ->on('wp_committee_paper')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wp_committee_paper_director_info');
    }
};
