<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumBestAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('forum_best_answers')) {

            Schema::create('forum_best_answers', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('topic_id');
                $table->integer('post_id');
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            });
        
        } else {

            Schema::table('forum_best_answers', function (Blueprint $table) {

                if (! Schema::hasColumn('forum_best_answers', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('forum_best_answers', 'user_id')) 
                    $table->integer('user_id');
                
                if (! Schema::hasColumn('forum_best_answers', 'topic_id')) 
                    $table->integer('topic_id');
                
                if (! Schema::hasColumn('forum_best_answers', 'post_id')) 
                    $table->integer('post_id');
                
                if (! Schema::hasColumn('forum_best_answers', 'created_at')) 
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
        Schema::dropIfExists('forum_best_answers');
    }
}
