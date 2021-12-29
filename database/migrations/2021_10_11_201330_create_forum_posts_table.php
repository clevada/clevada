<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forum_posts')) {

            Schema::create('forum_posts', function (Blueprint $table) {
                $table->id();
                $table->integer('topic_id');
                $table->smallInteger('categ_id');
                $table->integer('user_id');
                $table->longText('content');
                $table->timestamps();
                $table->string('status', 20);
                $table->integer('updated_by_user_id')->nullable();
                $table->integer('count_likes')->default(0);
                $table->integer('count_best_answer')->default(0);
            });
        } else {

            Schema::table('forum_posts', function (Blueprint $table) {

                if (!Schema::hasColumn('forum_posts', 'id'))
                    $table->id();

                if (!Schema::hasColumn('forum_posts', 'topic_id'))
                    $table->integer('topic_id');

                if (!Schema::hasColumn('forum_posts', 'categ_id'))
                    $table->smallInteger('categ_id');

                if (!Schema::hasColumn('forum_posts', 'user_id'))
                    $table->integer('user_id');

                if (!Schema::hasColumn('forum_posts', 'content'))
                    $table->longText('content');

                if (!Schema::hasColumn('forum_posts', 'id'))
                    $table->timestamps();

                if (!Schema::hasColumn('forum_posts', 'status'))
                    $table->string('status', 20);
                
                if (!Schema::hasColumn('forum_posts', 'updated_by_user_id'))
                    $table->integer('updated_by_user_id')->nullable();

                if (!Schema::hasColumn('forum_posts', 'count_likes'))
                    $table->integer('count_likes')->default(0);

                if (!Schema::hasColumn('forum_posts', 'count_best_answer'))
                    $table->integer('count_best_answer')->default(0);
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
        Schema::dropIfExists('forum_posts');
    }
}
