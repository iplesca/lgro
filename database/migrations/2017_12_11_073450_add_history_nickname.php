<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHistoryNickname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_history', function (Blueprint $table) {
            $table->string('nickname');
        });
        Schema::table('members', function (Blueprint $table) {
            $table->string('nickname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_history', function (Blueprint $table) {
            $table->dropColumn('nickname');
        });
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('nickname');
        });
    }
}
