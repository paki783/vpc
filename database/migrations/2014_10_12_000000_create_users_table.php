<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('user_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('facebook_link');
            $table->string('twitter_link');
            $table->string('youtube_link');
            $table->string('playstationtag');
            $table->string('xboxtag');
            $table->text('origin_account')->nullable();
            $table->string('streamid');
            $table->text('bio');
            $table->text('profile_image');
            $table->text('pending_profile_image')->nullable();
            $table->tinyInteger('pending_profile_status')->default(0);
            $table->enum('promote',['user', 'manager', 'admin']);
            $table->enum('user_type',['premium', 'free']);
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('selected_team');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('mode_id')->nullable();
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
