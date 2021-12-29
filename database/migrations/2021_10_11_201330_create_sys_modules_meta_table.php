<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysModulesMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_modules_meta')) {

            Schema::create('sys_modules_meta', function (Blueprint $table) {
                $table->id();
                $table->string('module_id');
                $table->integer('lang_id')->nullable();
                $table->text('meta_title')->nullable();
                $table->text('meta_description')->nullable();
            });
        } else {

            Schema::table('sys_modules_meta', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_modules_meta', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_modules_meta', 'module_id'))
                    $table->integer('module_id');

                if (!Schema::hasColumn('sys_modules_meta', 'lang_id'))
                    $table->integer('lang_id')->nullable();

                if (!Schema::hasColumn('sys_modules_meta', 'meta_title'))
                    $table->text('meta_title')->nullable();

                if (!Schema::hasColumn('sys_modules_meta', 'meta_description'))
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
        Schema::dropIfExists('sys_modules_meta');
    }
}
