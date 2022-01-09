<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocsContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('docs_content')) {

            Schema::create('docs_content', function (Blueprint $table) {
                $table->id();
                $table->integer('doc_id');
                $table->integer('lang_id')->nullable();
                $table->string('title', 250);
                $table->string('slug', 250);
                $table->text('search_terms')->nullable();
            });
        
        } else {

            Schema::table('docs_content', function (Blueprint $table) {

                if (! Schema::hasColumn('docs_content', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('docs_content', 'doc_id')) 
                    $table->integer('doc_id');
                
                if (! Schema::hasColumn('docs_content', 'lang_id')) 
                    $table->integer('lang_id')->nullable();
                
                if (! Schema::hasColumn('docs_content', 'title')) 
                    $table->string('title', 250);
                
                if (! Schema::hasColumn('docs_content', 'slug')) 
                    $table->string('slug', 250);
                
                if (! Schema::hasColumn('docs_content', 'search_terms')) 
                    $table->text('search_terms')->nullable();             
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
        Schema::dropIfExists('docs_content');
    }
}
