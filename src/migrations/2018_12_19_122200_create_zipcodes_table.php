<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZipcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zipcodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('district_id')->unsigned();
            $table->integer('county_id')->unsigned();
            $table->integer('locality_id')->nullable()->unsigned();
            $table->string('localidade');
            $table->string('zip_code')->index();
            $table->string('cp4')->index();
            $table->string('cp3')->index();
            $table->string('ART_COD');
            $table->string('ART_TIPO');
            $table->string('PRI_PREP');
            $table->string('ART_TITULO');
            $table->string('SEG_PREP');
            $table->string('ART_DESIG');
            $table->string('ART_LOCAL');
            $table->string('TROCO');
            $table->string('PORTA');
            $table->string('CLIENTE');
            $table->string('CPALF');
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
        Schema::dropIfExists('zipcodes');
    }
}
