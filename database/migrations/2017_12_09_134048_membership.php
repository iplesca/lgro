<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Membership extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')
                ->foreign('member_id')
                ->references('id')
                ->on('members');
            $table->timestamp('joined')->nullable();
            $table->timestamp('left')->nullable();
            $table->integer('days');
            $table->string('role');
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
