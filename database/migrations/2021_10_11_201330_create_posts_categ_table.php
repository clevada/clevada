<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsCategTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('posts_categ')) {

            Schema::create('posts_categ', function (Blueprint $table) {
                $table->id();
                $table->smallInteger('lang_id')->nullable();
                $table->integer('parent_id')->nullable();
                $table->string('tree_ids', 250)->nullable();
                $table->string('title', 150);
                $table->string('slug', 150);
                $table->text('description')->nullable();
                $table->tinyInteger('active');
                $table->smallInteger('position')->nullable();
                $table->text('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('badges')->nullable();
                $table->string('icon', 250)->nullable();
                $table->integer('count_items')->nullable();
                $table->integer('count_tree_items')->nullable();
                $table->integer('top_section_id')->nullable();
                $table->integer('bottom_section_id')->nullable();
                $table->integer('sidebar_id')->nullable();
                $table->string('sidebar_position', 50)->nullable();
            });
        } else {

            Schema::table('posts_categ', function (Blueprint $table) {

                if (!Schema::hasColumn('posts_categ', 'id'))
                    $table->id();

                if (!Schema::hasColumn('posts_categ', 'lang_id'))
                    $table->smallInteger('lang_id')->nullable();

                if (!Schema::hasColumn('posts_categ', 'parent_id'))
                    $table->integer('parent_id')->nullable();

                if (!Schema::hasColumn('posts_categ', 'tree_ids'))
                    $table->string('tree_ids', 250)->nullable();

                if (!Schema::hasColumn('posts_categ', 'title'))
                    $table->string('title', 150);

                if (!Schema::hasColumn('posts_categ', 'slug'))
                    $table->string('slug', 150);

                if (!Schema::hasColumn('posts_categ', 'description'))
                    $table->text('description')->nullable();

                if (!Schema::hasColumn('posts_categ', 'active'))
                    $table->tinyInteger('active');

                if (!Schema::hasColumn('posts_categ', 'position'))
                    $table->smallInteger('position')->nullable();

                if (!Schema::hasColumn('posts_categ', 'meta_title'))
                    $table->text('meta_title')->nullable();

                if (!Schema::hasColumn('posts_categ', 'meta_description'))
                    $table->text('meta_description')->nullable();

                if (!Schema::hasColumn('posts_categ', 'badges'))
                    $table->text('badges')->nullable();

                if (!Schema::hasColumn('posts_categ', 'icon'))
                    $table->string('icon', 250)->nullable();

                if (!Schema::hasColumn('posts_categ', 'count_items'))
                    $table->integer('count_items')->nullable();

                if (!Schema::hasColumn('posts_categ', 'count_tree_items'))
                    $table->integer('count_tree_items')->nullable();

                if (!Schema::hasColumn('posts_categ', 'top_section_id'))
                    $table->integer('top_section_id')->nullable();

                if (!Schema::hasColumn('posts_categ', 'bottom_section_id'))
                    $table->integer('bottom_section_id')->nullable();

                if (!Schema::hasColumn('posts_categ', 'sidebar_position'))
                    $table->string('sidebar_position', 50)->nullable();
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
        Schema::dropIfExists('posts_categ');
    }
}
