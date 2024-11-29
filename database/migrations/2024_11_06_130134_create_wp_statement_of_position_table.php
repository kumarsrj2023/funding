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
        Schema::create('wp_statement_of_position', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('wp_director_info_id');
            $table->string('director_name')->nullable();
            $table->string('email')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('location')->nullable();
            $table->string('document_name')->nullable();
            $table->string('submitted_by')->nullable();
            $table->foreign('wp_director_info_id', 'wp_statement_of_position_directorId_fk')
                ->references('id')
                ->on('wp_director_info')
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
        Schema::dropIfExists('wp_statement_of_position');
    }
};
