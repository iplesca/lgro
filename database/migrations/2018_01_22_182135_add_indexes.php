<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('member_id');
        });
        Schema::table('members', function (Blueprint $table) {
            $table->index('clan_id');
            $table->unique('wargaming_id');
        });
        Schema::table('accounts', function (Blueprint $table) {
            $table->index('member_id');
            $table->index('user_id');
        });
        Schema::table('member_tanks', function (Blueprint $table) {
            $table->index(['account_id', 'wargaming_id']);
        });
        Schema::table('member_tank_stats', function (Blueprint $table) {
            $table->index(['account_id', 'wargaming_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_tank_stats', function (Blueprint $table) {
            $table->dropIndex('member_tank_stats_account_id_wargaming_id_date_index');
            $table->dropIndex('member_tank_stats_date_index');
        });
        Schema::table('member_tanks', function (Blueprint $table) {
            $table->dropIndex('member_tanks_account_id_wargaming_id_index');
        });
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropIndex('accounts_member_id_index');
            $table->dropIndex('accounts_user_id_index');
        });
        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('members_clan_id_index');
            $table->dropUnique('members_wargaming_id_unique');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_member_id_index');
        });
    }
}
