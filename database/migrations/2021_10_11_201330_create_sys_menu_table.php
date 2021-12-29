<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_menu')) {

            Schema::create('sys_menu', function (Blueprint $table) {
                $table->id();
                $table->integer('parent_id')->nullable();
                $table->string('label', 100);
                $table->string('type', 50);
                $table->text('value')->nullable();
                $table->integer('position')->default(0);
            });
        } else {

            Schema::table('sys_menu', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_menu', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_menu', 'parent_id'))
                    $table->integer('parent_id')->nullable();

                if (!Schema::hasColumn('sys_menu', 'label'))
                    $table->string('label', 100);

                if (!Schema::hasColumn('sys_menu', 'type'))
                    $table->string('type', 50);

                if (!Schema::hasColumn('sys_menu', 'value'))
                    $table->text('value')->nullable();

                if (!Schema::hasColumn('sys_menu', 'position'))
                    $table->integer('position')->default(0);
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
        Schema::dropIfExists('sys_menu');
    }
}
