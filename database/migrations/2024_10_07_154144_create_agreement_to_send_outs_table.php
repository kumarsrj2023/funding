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
        Schema::create('wp_agreement_to_send_outs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('wp_business_info_id');
            $table->string('folder_to_save', 100)->nullable();
            $table->string('agreement_to_send_out')->nullable();
            $table->string('completed', 100)->nullable();
            $table->string('second_checker_completed', 100)->nullable();
            $table->string('notes', 100)->nullable();
            $table->string('order_index', 100)->nullable();
            $table->string('logged_user', 100)->nullable();
            $table->foreign('wp_business_info_id', 'agreement_to_send_out_fk')
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
        Schema::dropIfExists('wp_agreement_to_send_outs');
    }
};
