<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
            $table->longText('description');
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->string('emblem32');
            $table->string('emblem64');
            $table->string('emblem195');
            $table->string('color', 10);
            $table->string('motto');

            $table->timestamps();
        });

        DB::table('clans')->insert(
            [
                'wargaming_id' => '500052049',
                'name' => 'PiRoman',
                'tag' => 'FOC-D',
                'color' => '#FF0800',
                'motto' => 'Come and take them!',
                'emblem32' => 'http://eu.wargaming.net/clans/media/clans/emblems/cl_049/500052049/emblem_32x32.png',
                'emblem64' => 'http://eu.wargaming.net/clans/media/clans/emblems/cl_049/500052049/emblem_64x64.png',
                'emblem195' => 'http://eu.wargaming.net/clans/media/clans/emblems/cl_049/500052049/emblem_195x195.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),

                'description' => '<p>Site și serverTS3: <a href="http://piroman.isteam.ro" target="_blank">piroman.isteam.ro</a>
</p><p>
<br/><strong>Material de PiRoman</strong>, e vorba pe care o folosim des și care înseamnă respect, răbdare și dorința de a participa la o comunitate strânsă de jucători, ce își doresc să crească (și personal, și în clan). Bineînțeles, dacă ai talent să dai foc la tancuri atunci clar ai <i>material de piroman</i> :D
</p><p>
<br/><strong>CRITERII DE RECRUTARE</strong> (<i>discutabile, după caz</i>):
<br/><i>Nu ne interesează WN8 ci FP1!!</i>
<br/>0. <strong>Sociabil</strong>, <strong>prietenos</strong>, interesat de a învăța (orice!)
</p><p>
<br/>1. un tanc de <strong>Tier 10</strong>
<br/>2. două tancuri de <strong>Tier 8</strong>
<br/>3. două tancuri de <strong>Tier 6</strong>
</p><p>
<br/>Prezența pe TS este obligatorie după ora 18:00 (până când vă duc puterile).
</p><p>
<br/>Persoane de contact: 
<br/><a href="https://worldoftanks.eu/en/community/accounts/514353122-stefy2014" target="_blank">stefy2014</a> [Ofițer Personal], <a href="https://worldoftanks.eu/en/community/accounts/521266819-_Syu_" target="_blank">_Syu_</a> [Comandant Executiv], <a href="http://worldoftanks.eu/en/community/accounts/519931899-SirLucasIV" target="_blank">SirLucasIV</a> [Comandant]
</p>'
            ]
        );
        DB::table('clans')->insert(
            [
                'wargaming_id' => '500033466',
                'name' => 'Lupii Galbeni',
                'tag' => 'LG-RO',
                'color' => '#173F73',
                'motto' => 'RESPECT-PRIETENIE-ALTRUISM',
                'emblem32' => 'http://eu.wargaming.net/clans/media/clans/emblems/cl_466/500033466/emblem_32x32.png',
                'emblem64' => 'http://eu.wargaming.net/clans/media/clans/emblems/cl_466/500033466/emblem_64x64.png',
                'emblem195' => 'http://eu.wargaming.net/clans/media/clans/emblems/cl_466/500033466/emblem_195x195.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),

                'description' => '<p>Un Clan bazat pe Respect , Prietenie si Altruism
</p><p>
<br/>Comunicare prin team speak 3 pe adresa ts.play-arena.ro
</p>'
            ]
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
