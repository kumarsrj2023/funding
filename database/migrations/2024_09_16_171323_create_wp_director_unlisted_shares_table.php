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
        Schema::create('wp_director_unlisted_shares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('wp_director_info_id');
            $table->string('company_name', 100)->nullable();
            $table->string('reg_number', 100)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('registered', 100)->nullable();
            $table->string('shareholding', 100)->nullable();
            $table->foreign('wp_director_info_id', 'wp_dir_unlisted_shares')
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
        Schema::dropIfExists('wp_director_unlisted_shares');
    }
};
