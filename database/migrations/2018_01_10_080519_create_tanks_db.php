<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTanksDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('wg_tanks', function (Blueprint $table) {
            $table->integer('wargaming_id')
                ->primary('wargaming_id'); // tank_id
            $table->string('nation'); // nation
            $table->smallInteger('tier'); // level
            $table->enum('type', ['LT', 'MT', 'HT', 'TD', 'SPG', 'error']); // type
            $table->string('name'); // name_i18n
            $table->string('name_short'); // short_name_i18n
            $table->string('name_uri'); // name
            $table->enum('premium', ['no', 'yes']); // is_premium
            $table->string('image'); // image
            $table->string('image_small'); // image_small
            $table->string('image_contour'); // contour_image
            $table->timestamps();
            /*
            [nation_i18n] => Germany
            [name] => #germany_vehicles:Pro_Ag_A
            [level] => 9
            [image] => http://eu-wotp.wgcdn.co/static/2.57.0/encyclopedia/tankopedia/vehicle/germany-g91_pro_ag_a.png
            [image_small] => http://eu-wotp.wgcdn.co/static/2.57.0/encyclopedia/tankopedia/vehicle/small/germany-g91_pro_ag_a.png
            [nation] => germany
            [is_premium] =>
            [type_i18n] => Medium TankDefinition
            [contour_image] => http://eu-wotp.wgcdn.co/static/2.57.0/encyclopedia/tankopedia/vehicle/contour/germany-g91_pro_ag_a.png
            [short_name_i18n] => Leopard PT A
            [name_i18n] => Leopard Prototyp A
            [type] => mediumTank
            [tank_id] => 14865
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wg_tanks');
    }
}
