<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('posts_tags')) {

            Schema::create('posts_tags', function (Blueprint $table) {
                $table->id();
                $table->string('tag', 200);
                $table->string('slug', 200);
                $table->integer('post_id');
                $table->integer('lang_id');
                $table->integer('counter')->default(0);
            });
        
        } else {

            Schema::table('posts_tags', function (Blueprint $table) {

                if (! Schema::hasColumn('posts_tags', 'id')) 
                    $table->id();
                    
                if (! Schema::hasColumn('posts_tags', 'tag')) 
                    $table->string('tag', 200);
                
                if (! Schema::hasColumn('posts_tags', 'slug')) 
                    $table->string('slug', 200);
                
                if (! Schema::hasColumn('posts_tags', 'post_id')) 
                    $table->integer('post_id');
                
                if (! Schema::hasColumn('posts_tags', 'lang_id')) 
                    $table->integer('lang_id');
                
                if (! Schema::hasColumn('posts_tags', 'counter')) 
                    $table->integer('counter')->default(0);
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
        Schema::dropIfExists('posts_tags');
    }
}
