<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                    ->foreign('user_id')
                    ->references('id')
                ->on('users');
            $table->longText('json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_stats');
    }
}
