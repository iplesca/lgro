<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClanMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clans_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wargaming_id', false)->unique();
            $table->longText('rules')
                ->default(null)
                ->nullable();
            $table->longText('welcome')
                ->default(null)
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clans_meta');
    }
}

