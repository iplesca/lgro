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
        Schema::disableForeignKeyConstraints();
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('first')->default(true); // first login in clan
            $table->integer('clan_id')
                ->foreign('clan_id')
                ->references('id')
                ->on('clans');

            $table->integer('account_id')
                ->nullable()
                ->foreign('account_id')
                ->references('id')
                ->on('accounts');
            $table->integer('user_id')
                ->nullable()
                ->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->integer('wargaming_id');
            $table->string('nickname');
            $table->string('role')->default('recruit');
            $table->string('granted')->default('recruit');
            $table->boolean('online')->default(false);
            $table->boolean('hibernate')->default(true);
            $table->integer('wn8')->nullable()
                    ->default(0);
            $table->integer('wn8_30')->nullable()
                    ->default(0);
            $table->timestamp('joined')->nullable()->default(null);

            $table->integer('score', false)->nullable()
                    ->default(0);
            $table->boolean('premium')->nullable()
                    ->default(false);
            $table->timestamp('premium_expire')->nullable()
                    ->default(null);
            $table->integer('credits')->nullable()
                    ->default(0);
            $table->integer('gold', false)->nullable()
                    ->default(0);
            $table->integer('bonds')->nullable()
                    ->default(0);
            $table->integer('free_xp', false)->nullable()
                    ->default(0);
            $table->integer('ban_time', false)->nullable();
            $table->string('ban_info')->nullable();
            $table->boolean('phone_link')->nullable();
            $table->integer('battle_time')->nullable();
            $table->timestamp('logout')->nullable()
                    ->default(null);
            $table->longText('stats')->nullable();
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
        Schema::dropIfExists('members');
    }
}
