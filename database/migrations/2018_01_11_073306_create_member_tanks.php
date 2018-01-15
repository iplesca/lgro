<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_tanks', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('member_id')
                ->foreign('member_id')
                ->references('id')
                ->on('members');

            $table->integer('wargaming_id'); // tank_id;
            $table->timestampTz('updated');
            $table->boolean('in_garage')
                ->default(false);
            $table->integer('wn8')->nullable()
                ->default(0);
            $table->integer('wn8_30')->nullable()
                ->default(0);
            $table->enum('mastery', ['0', '3', '2', '1', 'M']); // mark_of_mastery
            $table->integer('max_xp')
                ->default(0);
            $table->integer('max_frags')
                ->default(0);
            // stats
            $table->integer('battles')
                ->default(0);
            $table->integer('wins')
                ->default(0);
            $table->integer('losses')
                ->default(0);
            $table->integer('dropped_capture_points')
                ->default(0);
            $table->integer('capture_points')
                ->default(0);
            $table->integer('xp')
                ->default(0);
            $table->integer('frags')
                ->default(0);
            $table->integer('damage_dealt')
                ->default(0);
            $table->integer('spotted')
                ->default(0);
            $table->integer('battles_on_stunning_vehicles')
                ->default(0);
            $table->integer('survived_battles')
                ->default(0);
            $table->integer('hits_percents')
                ->default(0);
            $table->integer('draws')
                ->default(0);
            $table->integer('damage_received')
                ->default(0);
            $table->integer('stun_number')
                ->default(0);
            $table->integer('stun_assisted_damage')
                ->default(0);
            $table->integer('shots')
                ->default(0);
            $table->integer('hits')
                ->default(0);
            $table->integer('battle_avg_xp')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_tanks');
    }
}
