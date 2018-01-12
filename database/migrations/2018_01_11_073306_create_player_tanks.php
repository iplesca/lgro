<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerTanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_tanks', function (Blueprint $table) {
            $table->integer('wargaming_id')
                ->primary('wargaming_id'); // tank_id;
            $table->enum('mastery', ['0', '3', '2', '1', 'M']); // mark_of_mastery
            $table->integer('total_wins')
                ->default(0); // statistics.wins
            $table->integer('total_battles')
                ->default(0); // statistics.battles
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
