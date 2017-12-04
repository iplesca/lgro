<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();

            $table->integer('wargaming_id', false)->unique();
            $table->integer('wot_rating', false);
            $table->string('wot_language', 3);

            $table->integer('wot_gold', false);
            $table->integer('wot_free_xp', false);
            $table->integer('wot_ban_time', false)->nullable();
            $table->string('wot_ban_info')->nullable();
            $table->boolean('wot_phone_link');
            $table->boolean('wot_premium');
            $table->integer('wot_credits');
            $table->integer('wot_bonds');
            $table->integer('wot_battle_time');
            $table->timestamp('wot_premium_expire')->nullable()->default(null);

            $table->string('nickname', 25);
            $table->string('wot_token', 100);
            $table->timestamp('wot_token_expire')->nullable()->default(null);
            $table->timestamp('wot_logout')->nullable()->default(null);
            $table->timestamp('wot_created_at')->nullable()->default(null);
            $table->timestamp('wot_updated_at')->nullable()->default(null);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
