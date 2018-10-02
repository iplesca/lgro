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
        Schema::disableForeignKeyConstraints();
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('member_id')
                ->nullable()
                ->foreign('member_id')
                ->references('id')
                ->on('members');
            $table->boolean('first')->default(true); // first login on the platform
            $table->integer('wargaming_id', false)->unique();
            $table->string('nickname', 25);
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();

            $table->string('wot_language', 3)->default('en');

            $table->string('wot_token', 100);
            $table->timestamp('wot_token_expire')->nullable()->default(null);
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
