<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('division_id');
            $table->unsignedBigInteger('league_id');
            $table->unsignedBigInteger('team_one_id');
            $table->unsignedBigInteger('team_two_id');
            $table->text('match_start_date');
            $table->text('match_end_date');
            $table->text('match_start_timestamp');
            $table->text('match_end_timestamp');
            $table->bigInteger('home_score');
            $table->bigInteger('away_score');
            $table->enum('match_status', ['scheduled','disputed','completed','in progress','pending'])->default("pending");
            $table->enum('match_type', ['match','disputed','tournament','bracket'])->default(null);
            $table->text('season');
            $table->timestamps();

            // $table->foreign('league_id')->references('id')->on('tournaments')->onDelete('cascade');
            // $table->foreign('team_one_id')->references('id')->on('teams')->onDelete('cascade');
            // $table->foreign('team_two_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
