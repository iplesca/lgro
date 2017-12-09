<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('clan_id')
                ->foreign('clan_id')
                ->references('id')
                ->on('clans');
            $table->integer('user_id')
                ->nullable()
                ->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->integer('wargaming_id');
            $table->timestamp('joined')->nullable()->default(null);
            $table->string('role')->default('recruit');
            $table->string('granted')->default('recruit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
