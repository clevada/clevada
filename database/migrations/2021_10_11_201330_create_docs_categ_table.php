<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocsCategTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('docs_categ')) {

            Schema::create('docs_categ', function (Blueprint $table) {
                $table->id();
                $table->integer('parent_id')->nullable();
                $table->string('tree_ids', 250)->nullable();
                $table->string('label', 150);
                $table->tinyInteger('active');
                $table->smallInteger('position')->nullable();
                $table->tinyInteger('featured')->default(0);
                $table->string('icon', 250)->nullable();
                $table->integer('count_items')->nullable();
                $table->integer('count_tree_items')->nullable();
            });
        } else {

            Schema::table('docs_categ', function (Blueprint $table) {

                if (!Schema::hasColumn('docs_categ', 'id'))
                    $table->id();

                if (!Schema::hasColumn('docs_categ', 'parent_id'))
                    $table->integer('parent_id')->nullable();

                if (!Schema::hasColumn('docs_categ', 'tree_ids'))
                    $table->string('tree_ids', 250)->nullable();

                if (!Schema::hasColumn('docs_categ', 'label'))
                    $table->string('label', 150);

                if (!Schema::hasColumn('docs_categ', 'active'))
                    $table->tinyInteger('active');

                if (!Schema::hasColumn('docs_categ', 'featured'))
                    $table->tinyInteger('featured')->default(0);

                if (!Schema::hasColumn('docs_categ', 'position'))
                    $table->smallInteger('position')->nullable();

                if (!Schema::hasColumn('docs_categ', 'icon'))
                    $table->string('icon', 250)->nullable();

                if (!Schema::hasColumn('docs_categ', 'count_items'))
                    $table->integer('count_items')->nullable();

                if (!Schema::hasColumn('docs_categ', 'count_tree_items'))
                    $table->integer('count_tree_items')->nullable();
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
        Schema::dropIfExists('docs_categ');
    }
}
