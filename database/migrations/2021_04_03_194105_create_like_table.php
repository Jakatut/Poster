<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('like', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('post')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('like', function (Blueprint $table) {
            $table->dropForeign('like_user_id_foreign');
            $table->dropForeign('like_post_id_foreign');
        });
        Schema::dropIfExists('like');
    }
}
