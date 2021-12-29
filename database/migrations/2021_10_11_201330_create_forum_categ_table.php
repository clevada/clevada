<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumCategTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('forum_categ')) {

            Schema::create('forum_categ', function (Blueprint $table) {
                $table->id();
                $table->integer('parent_id')->nullable();
                $table->string('tree_ids', 250)->nullable();
                $table->string('title', 150);
                $table->string('slug', 150);
                $table->text('description')->nullable();
                $table->tinyInteger('active');
                $table->smallInteger('position')->nullable();
                $table->string('type', 50)->nullable();
                $table->tinyInteger('allow_topics')->default(1);
                $table->text('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('badges')->nullable();
                $table->string('icon', 250)->nullable();
                $table->integer('count_topics')->nullable();
                $table->integer('count_tree_topics')->nullable();
                $table->integer('count_posts')->nullable();
                $table->integer('count_tree_posts')->nullable();
            });
        
        } else {

            Schema::table('forum_categ', function (Blueprint $table) {

                if (! Schema::hasColumn('forum_categ', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('forum_categ', 'parent_id')) 
                    $table->integer('parent_id')->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'tree_ids')) 
                    $table->string('tree_ids', 250)->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'title')) 
                    $table->string('title', 150);
                
                if (! Schema::hasColumn('forum_categ', 'slug')) 
                    $table->string('slug', 150);
                
                if (! Schema::hasColumn('forum_categ', 'description')) 
                    $table->text('description')->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'active')) 
                    $table->tinyInteger('active');
                
                if (! Schema::hasColumn('forum_categ', 'position')) 
                    $table->smallInteger('position')->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'type')) 
                    $table->string('type', 50)->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'allow_topics')) 
                    $table->tinyInteger('allow_topics')->default(1);
                
                if (! Schema::hasColumn('forum_categ', 'meta_title')) 
                    $table->text('meta_title')->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'meta_description')) 
                    $table->text('meta_description')->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'badges')) 
                    $table->text('badges')->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'icon')) 
                    $table->string('icon', 250)->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'count_topics')) 
                    $table->integer('count_topics')->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'count_tree_topics')) 
                    $table->integer('count_tree_topics')->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'count_posts')) 
                    $table->integer('count_posts')->nullable();
                
                if (! Schema::hasColumn('forum_categ', 'count_tree_posts')) 
                    $table->integer('count_tree_posts')->nullable();

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
        Schema::dropIfExists('forum_categ');
    }
}
