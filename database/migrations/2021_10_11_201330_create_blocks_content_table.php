<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('blocks_content')) {

            Schema::create('blocks_content', function (Blueprint $table) {
                $table->id();
                $table->integer('block_id');
                $table->integer('lang_id');
                $table->mediumText('content')->nullable();
                $table->text('header')->nullable();
            });

        } else {

            Schema::table('blocks_content', function (Blueprint $table) {

                if (! Schema::hasColumn('blocks_content', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('blocks_content', 'block_id')) 
                    $table->integer('block_id');

                if (! Schema::hasColumn('blocks_content', 'lang_id'))     
                    $table->integer('lang_id');

                if (! Schema::hasColumn('blocks_content', 'content'))         
                    $table->mediumText('content')->nullable();

                if (! Schema::hasColumn('blocks_content', 'header'))                         
                    $table->text('header', 250)->nullable();

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
        Schema::dropIfExists('blocks_content');
    }
}
