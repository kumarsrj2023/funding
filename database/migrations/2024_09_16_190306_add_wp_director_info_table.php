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
        Schema::table('wp_director_info', function (Blueprint $table) {
            $table->renameColumn('phone', 'mobile');
            $table->text('middle_name')->nullable()->after('first_name');
            $table->text('time_in_curr_address')->nullable()->after('wp_business_info_id');
            $table->string('tel_home', 15)->nullable()->after('wp_business_info_id');
            $table->string('tel_business', 15)->nullable()->after('wp_business_info_id');
            $table->string('declared_bankrupt', 15)->nullable()->after('wp_business_info_id');
            $table->string('signature', 100)->nullable()->after('wp_business_info_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wp_director_info', function (Blueprint $table) {
            $table->renameColumn('mobile', 'phone');
            $table->dropColumn(['signature', 'middle_name', 'time_in_curr_address', 'tel_home', 'tel_business', 'declared_bankrupt']);
        });
    }
};
