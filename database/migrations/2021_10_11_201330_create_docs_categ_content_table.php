<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocsCategContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('docs_categ_content')) {

            Schema::create('docs_categ', function (Blueprint $table) {
                $table->id();
                $table->integer('categ_id');
                $table->integer('lang_id')->nullable();
                $table->string('title', 150);
                $table->string('slug', 150);
                $table->text('description')->nullable();
                $table->text('meta_title')->nullable();
                $table->text('meta_description')->nullable();
            });
        
        } else {

            Schema::table('docs_categ_content', function (Blueprint $table) {

                if (! Schema::hasColumn('docs_categ_content', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('docs_categ_content', 'categ_id')) 
                    $table->integer('categ_id');
                
                if (! Schema::hasColumn('docs_categ_content', 'lang_id')) 
                    $table->integer('lang_id')->nullable();
                             
                if (! Schema::hasColumn('docs_categ_content', 'title')) 
                    $table->string('title', 150);
                
                if (! Schema::hasColumn('docs_categ_content', 'slug')) 
                    $table->string('slug', 150);
                
                if (! Schema::hasColumn('docs_categ_content', 'description')) 
                    $table->text('description')->nullable();                          
                
                if (! Schema::hasColumn('docs_categ_content', 'meta_title')) 
                    $table->text('meta_title')->nullable();
                
                if (! Schema::hasColumn('docs_categ_content', 'meta_description')) 
                    $table->text('meta_description')->nullable();                                
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
        Schema::dropIfExists('docs_categ_content');
    }
}
