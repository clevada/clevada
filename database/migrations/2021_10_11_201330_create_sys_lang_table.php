<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('sys_lang')) {

            Schema::create('sys_lang', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50);
                $table->string('code', 25);
                $table->string('locale', 50)->nullable();
                $table->tinyInteger('is_default')->default(0);
                $table->string('status', 25)->nullable();
                $table->string('timezone', 50)->nullable();
                $table->string('site_short_title', 200)->nullable();
                $table->text('homepage_meta_title')->nullable();
                $table->text('homepage_meta_description')->nullable();
                $table->text('permalinks')->nullable();
            });
        
        } else {

            Schema::table('sys_lang', function (Blueprint $table) {

                if (! Schema::hasColumn('sys_lang', 'id')) 
                    $table->id();                             
                
                if (! Schema::hasColumn('sys_lang', 'name')) 
                    $table->string('name', 50);

                if (! Schema::hasColumn('sys_lang', 'code')) 
                    $table->string('code', 25);
                
                if (! Schema::hasColumn('sys_lang', 'locale')) 
                    $table->string('locale', 50)->nullable();
                
                if (! Schema::hasColumn('sys_lang', 'is_default')) 
                    $table->tinyInteger('is_default')->default(0);
                
                if (! Schema::hasColumn('sys_lang', 'status')) 
                    $table->string('status', 25)->nullable();
                
                if (! Schema::hasColumn('sys_lang', 'timezone')) 
                    $table->string('timezone', 50)->nullable();
                
                if (! Schema::hasColumn('sys_lang', 'site_short_title')) 
                    $table->string('site_short_title', 200)->nullable();
                
                if (! Schema::hasColumn('sys_lang', 'homepage_meta_title')) 
                    $table->text('homepage_meta_title')->nullable();                        
                
                if (! Schema::hasColumn('sys_lang', 'homepage_meta_description')) 
                    $table->text('homepage_meta_description')->nullable();
                
                if (! Schema::hasColumn('sys_lang', 'permalinks')) 
                    $table->text('permalinks')->nullable();            
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
        Schema::dropIfExists('sys_lang');
    }
}
