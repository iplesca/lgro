<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembership extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('membership_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wargaming_id');
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
        Schema::dropIfExists('membership_history');
    }
}
