<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVpcTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->text('name');
            $table->text('description');
            $table->unsignedBigInteger('vpc_systemid');
            $table->text('logo');
            $table->text('banner');
            $table->enum('tournament_type', ['league', 'tournament']);
            $table->timestamps();

            $table->foreign('vpc_systemid')->references('id')->on('vpcsystems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vpc_tournaments');
    }
}
