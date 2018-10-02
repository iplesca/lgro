<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('member_id')
                ->nullable()
                ->default(null)
                ->foreign('member_id')
                ->references('id')
                ->on('members');
            $table->integer('user_id')
                ->nullable()
                ->default(null)
                ->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->integer('wargaming_id', false)->unique();

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
        Schema::dropIfExists('accounts');
    }
}
