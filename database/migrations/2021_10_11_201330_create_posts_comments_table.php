<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('posts_comments')) {

            Schema::create('posts_comments', function (Blueprint $table) {
                $table->id();
                $table->integer('post_id');
                $table->integer('user_id')->nullable();
                $table->string('status', 50);
                $table->string('name', 250)->nullable();
                $table->string('email', 250)->nullable();
                $table->mediumText('comment');
                $table->string('ip', 50);
                $table->timestamps();
                $table->integer('updated_by_user_id')->nullable();
            });
        } else {

            Schema::table('posts_comments', function (Blueprint $table) {

                if (!Schema::hasColumn('posts_comments', 'id'))
                    $table->id();

                if (!Schema::hasColumn('posts_comments', 'post_id'))
                    $table->integer('post_id');

                if (!Schema::hasColumn('posts_comments', 'user_id'))
                    $table->integer('user_id')->nullable();

                if (!Schema::hasColumn('posts_comments', 'status'))
                    $table->string('status', 50);

                if (!Schema::hasColumn('posts_comments', 'name'))
                    $table->string('name', 250)->nullable();

                if (!Schema::hasColumn('posts_comments', 'email'))
                    $table->string('email', 250)->nullable();

                if (!Schema::hasColumn('posts_comments', 'comment'))
                    $table->mediumText('comment');

                if (!Schema::hasColumn('posts_comments', 'ip'))
                    $table->string('ip', 50);

                if (!Schema::hasColumn('posts_comments', 'id'))
                    $table->timestamps();

                if (!Schema::hasColumn('posts_comments', 'updated_by_user_id'))
                    $table->integer('updated_by_user_id')->nullable();
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
        Schema::dropIfExists('posts_comments');
    }
}
