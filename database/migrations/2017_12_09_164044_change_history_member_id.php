<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHistoryMemberId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_history', function (Blueprint $table) {
//            $table->dropForeign('membership_history_member_id_foreign');
            $table->renameColumn('member_id', 'wargaming_id');
            $table->string('reason')->default('no reason');
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
            $table->dropColumn('reason');
            $table->renameColumn('wargaming_id', 'member_id');
        });
    }
}
