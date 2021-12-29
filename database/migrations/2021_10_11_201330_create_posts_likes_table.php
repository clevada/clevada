<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('posts_likes')) {

            Schema::create('posts_likes', function (Blueprint $table) {
                $table->id();
                $table->integer('post_id');
                $table->integer('user_id')->nullable();
                $table->string('ip', 100);
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            });
        } else {

            Schema::table('posts_likes', function (Blueprint $table) {

                if (!Schema::hasColumn('posts_likes', 'id'))
                    $table->id();

                if (!Schema::hasColumn('posts_likes', 'post_id'))
                    $table->integer('post_id');

                if (!Schema::hasColumn('posts_likes', 'user_id'))
                    $table->integer('user_id')->nullable();

                if (!Schema::hasColumn('posts_likes', 'ip'))
                    $table->string('ip', 100);

                if (!Schema::hasColumn('posts_likes', 'created_at'))
                    $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_likes');
    }
}
