<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wargaming_id', false)->unique();
            $table->string('name', 40);
            $table->string('tag', 5);
            $table->timestamps();
        });

        DB::table('clans')->insert(
            array(
                'wargaming_id' => env('CLAN_ID'),
                'name' => 'Lupii Galbeni',
                'tag' => 'LG-RO',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clans');
    }
}
