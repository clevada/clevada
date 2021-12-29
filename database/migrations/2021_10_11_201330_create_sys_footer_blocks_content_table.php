<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysFooterBlocksContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_footer_blocks_content')) {

            Schema::create('sys_footer_blocks_content', function (Blueprint $table) {
                $table->id();
                $table->integer('block_id');
                $table->integer('lang_id')->nullable();
                $table->mediumtext('content')->nullable();
                $table->text('header')->nullable();                
            });
        } else {

            Schema::table('sys_footer_blocks_content', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_footer_blocks_content', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_footer_blocks_content', 'block_id'))
                    $table->integer('block_id');

                if (!Schema::hasColumn('sys_footer_blocks_content', 'lang_id'))
                    $table->integer('lang_id')->nullable();

                if (!Schema::hasColumn('sys_footer_blocks_content', 'content'))
                    $table->mediumtext('content')->nullable();

                if (!Schema::hasColumn('sys_footer_blocks_content', 'header'))
                    $table->text('header')->nullable();              
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
        Schema::dropIfExists('sys_footer_blocks_content');
    }
}
