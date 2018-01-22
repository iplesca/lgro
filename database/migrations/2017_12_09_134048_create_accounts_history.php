<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('accounts_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')
                ->foreign('account_id')
                ->references('id')
                ->on('accounts');
            $table->integer('clan_wargaming_id');
            $table->string('reason')->default('no reason');
            $table->string('nickname');
            $table->string('role');
            $table->timestamp('joined')->nullable();
            $table->timestamp('left')->nullable();
            $table->integer('days');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts_history');
    }
}
