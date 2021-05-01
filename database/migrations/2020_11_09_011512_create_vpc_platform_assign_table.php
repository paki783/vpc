<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVpcPlatformAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vpc_platform_assign', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('vpc_id');
            $table->unsignedBigInteger('platform_id');
            $table->timestamps();

            $table->foreign('vpc_id')->references('id')->on('vpcsystems')->onDelete('cascade');
            $table->foreign('platform_id')->references('id')->on('plateforms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vpc_platform_assign');
    }
}
