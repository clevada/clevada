<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forum_topics')) {

            Schema::create('forum_topics', function (Blueprint $table) {
                $table->id();
                $table->string('type', 50);
                $table->integer('user_id');
                $table->smallInteger('categ_id')->nullable();
                $table->string('title', 255);
                $table->string('slug', 255);
                $table->longText('content');
                $table->mediumInteger('count_posts');
                $table->integer('hits')->default(0);
                $table->string('status', 25)->nullable();
                $table->tinyInteger('sticky')->nullable();
                $table->integer('count_likes')->default(0);
                $table->dateTime('last_activity_at')->nullable();
                $table->integer('updated_by_user_id')->nullable();
                $table->timestamps();
            });
        } else {

            Schema::table('forum_topics', function (Blueprint $table) {

                if (!Schema::hasColumn('forum_topics', 'id'))
                    $table->id();

                if (!Schema::hasColumn('forum_topics', 'type'))
                    $table->string('type', 50);

                if (!Schema::hasColumn('forum_topics', 'user_id'))
                    $table->integer('user_id');

                if (!Schema::hasColumn('forum_topics', 'categ_id'))
                    $table->smallInteger('categ_id')->nullable();

                if (!Schema::hasColumn('forum_topics', 'title'))
                    $table->string('title', 255);

                if (!Schema::hasColumn('forum_topics', 'slug'))
                    $table->string('slug', 255);

                if (!Schema::hasColumn('forum_topics', 'content'))
                    $table->longText('content');            

                if (!Schema::hasColumn('forum_topics', 'count_posts'))
                    $table->mediumInteger('count_posts');

                if (!Schema::hasColumn('forum_topics', 'hits'))
                    $table->integer('hits')->default(0);

                if (!Schema::hasColumn('forum_topics', 'status'))
                    $table->string('status', 25)->nullable();

                if (!Schema::hasColumn('forum_topics', 'sticky'))
                    $table->tinyInteger('sticky')->nullable();

                if (!Schema::hasColumn('forum_topics', 'count_likes'))
                    $table->integer('count_likes')->default(0);

                if (!Schema::hasColumn('forum_topics', 'last_activity_at'))
                    $table->dateTime('last_activity_at')->nullable();

                if (!Schema::hasColumn('forum_topics', 'updated_by_user_id'))
                    $table->integer('updated_by_user_id')->nullable();

                if (!Schema::hasColumn('forum_topics', 'id'))
                    $table->timestamps();
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
        Schema::dropIfExists('forum_topics');
    }
}
