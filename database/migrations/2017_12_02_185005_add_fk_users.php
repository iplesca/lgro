<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('clan_id')
                ->foreign('clan_id')
                ->references('id')
                ->on('clans');
            $table->integer('stats_id')->nullable()
                ->foreign('stats_id')
                ->references('id')
                ->on('raw_stats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_clan_id_foreign');
            $table->dropForeign('users_stats_id_foreign');
        });
    }
}
