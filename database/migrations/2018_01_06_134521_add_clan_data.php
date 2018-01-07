<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClanData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clans', function (Blueprint $table) {
            $table->longText('description');
            $table->enum('status', ['active', 'inactive', 'blocked']);
            $table->string('emblem32');
            $table->string('emblem64');
            $table->string('emblem195');
            $table->string('color', 10);
            $table->string('motto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clans', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('status');
            $table->dropColumn('color');
            $table->dropColumn('emblem32');
            $table->dropColumn('emblem64');
            $table->dropColumn('emblem195');
            $table->dropColumn('motto');
        });
    }
}
