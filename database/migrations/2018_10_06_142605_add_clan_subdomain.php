<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClanSubdomain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clans', function (Blueprint $table) {
            $table->string('subdomain', 40)
                ->default(NULL)
                ->nullable()
                ->after('tag');
            $table->string('template', 30)
                ->default(NULL)
                ->nullable()
                ->after('subdomain');
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
            $table->dropColumn('subdomain');
            $table->dropColumn('template');
        });
    }
}
