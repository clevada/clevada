<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysMenuLangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_menu_langs')) {

            Schema::create('sys_menu_langs', function (Blueprint $table) {
                $table->id();
                $table->integer('link_id');
                $table->integer('lang_id');
                $table->string('label', 200)->nullable();                
            });
        } else {

            Schema::table('sys_menu_langs', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_menu_langs', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_menu_langs', 'link_id'))
                    $table->integer('link_id');

                if (!Schema::hasColumn('sys_menu_langs', 'lang_id'))
                    $table->integer('lang_id');

                if (!Schema::hasColumn('sys_menu_langs', 'label'))
                    $table->string('label', 200)->nullable();                
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
        Schema::dropIfExists('sys_menu_langs');
    }
}
