<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysFooterBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_footer_blocks')) {

            Schema::create('sys_footer_blocks', function (Blueprint $table) {
                $table->id();
                $table->integer('template_id');
                $table->integer('type_id');
                $table->text('extra')->nullable();
                $table->string('footer', 100);
                $table->tinyInteger('layout');
                $table->tinyInteger('col');
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        } else {

            Schema::table('sys_footer_blocks', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_footer_blocks', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_footer_blocks', 'template_id'))
                    $table->integer('template_id');

                if (!Schema::hasColumn('sys_footer_blocks', 'type_id'))
                    $table->integer('type_id');

                if (!Schema::hasColumn('sys_footer_blocks', 'extra'))
                    $table->text('extra')->nullable();

                if (!Schema::hasColumn('sys_footer_blocks', 'footer'))
                    $table->string('footer', 100);

                if (!Schema::hasColumn('sys_footer_blocks', 'layout'))
                    $table->tinyInteger('layout')->nullable();

                if (!Schema::hasColumn('sys_footer_blocks', 'col'))
                    $table->tinyInteger('col')->nullable();

                if (!Schema::hasColumn('sys_footer_blocks', 'position'))
                    $table->integer('position')->default(0);

                if (!Schema::hasColumn('sys_footer_blocks', 'id'))
                    $table->timestamps();
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
        Schema::dropIfExists('sys_footer_blocks');
    }
}
