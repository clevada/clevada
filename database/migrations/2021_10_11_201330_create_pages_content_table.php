<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('pages_content')) {

            Schema::create('pages_content', function (Blueprint $table) {
                $table->id();
                $table->integer('page_id');
                $table->smallInteger('lang_id')->nullable();
                $table->string('title', 250);
                $table->string('slug', 250);
                $table->mediumText('meta_title')->nullable();
                $table->mediumText('meta_description')->nullable();
            });
        
        } else {

            Schema::table('pages_content', function (Blueprint $table) {

                if (! Schema::hasColumn('pages_content', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('pages_content', 'page_id')) 
                    $table->integer('page_id');
                
                if (! Schema::hasColumn('pages_content', 'lang_id')) 
                    $table->smallInteger('lang_id')->nullable();
                
                if (! Schema::hasColumn('pages_content', 'title')) 
                    $table->string('title', 250);
                
                if (! Schema::hasColumn('pages_content', 'slug')) 
                    $table->string('slug', 250);                         
                
                if (! Schema::hasColumn('pages_content', 'meta_title')) 
                    $table->mediumText('meta_title')->nullable();
                
                if (! Schema::hasColumn('pages_content', 'meta_description')) 
                    $table->mediumText('meta_description')->nullable();                         
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
        Schema::dropIfExists('pages_content');
    }
}
