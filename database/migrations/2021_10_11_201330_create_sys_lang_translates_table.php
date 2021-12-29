<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysLangTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('sys_lang_translates')) {

            Schema::create('sys_lang_translates', function (Blueprint $table) {
                $table->id();
                $table->integer('key_id');
                $table->integer('lang_id');
                $table->text('translate')->nullable();
            });
        
        } else {

            Schema::table('sys_lang_translates', function (Blueprint $table) {

                if (! Schema::hasColumn('sys_lang_translates', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('sys_lang_translates', 'key_id')) 
                    $table->integer('key_id');

                if (! Schema::hasColumn('sys_lang_translates', 'lang_id')) 
                    $table->integer('lang_id');
                
                if (! Schema::hasColumn('sys_lang_translates', 'translate')) 
                    $table->text('translate')->nullable();
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
        Schema::dropIfExists('sys_lang_translates');
    }
}
