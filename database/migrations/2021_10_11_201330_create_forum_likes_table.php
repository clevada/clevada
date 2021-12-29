<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('forum_likes')) {

            Schema::create('forum_likes', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('topic_id')->nullable();
                $table->integer('post_id')->nullable();
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            });
        
        } else {

            Schema::table('forum_likes', function (Blueprint $table) {

                if (! Schema::hasColumn('forum_likes', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('forum_likes', 'user_id')) 
                    $table->integer('user_id');
                
                if (! Schema::hasColumn('forum_likes', 'topic_id')) 
                    $table->integer('topic_id')->nullable();
                
                if (! Schema::hasColumn('forum_likes', 'post_id'))
                    $table->integer('post_id')->nullable();
                
                if (! Schema::hasColumn('forum_likes', 'created_at')) 
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
        Schema::dropIfExists('forum_likes');
    }
}
