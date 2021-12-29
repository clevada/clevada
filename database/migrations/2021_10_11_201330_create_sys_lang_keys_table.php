<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysLangKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('sys_lang_keys')) {

            Schema::create('sys_lang_keys', function (Blueprint $table) {
                $table->id();
                $table->string('area', 50)->nullable();
                $table->text('lang_key');
            });
        
        } else {

            Schema::table('sys_lang_keys', function (Blueprint $table) {

                if (! Schema::hasColumn('sys_lang_keys', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('sys_lang_keys', 'area')) 
                    $table->string('area', 50)->nullable();
                
                if (! Schema::hasColumn('sys_lang_keys', 'lang_key')) 
                    $table->text('lang_key');
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
        Schema::dropIfExists('sys_lang_keys');
    }
}
