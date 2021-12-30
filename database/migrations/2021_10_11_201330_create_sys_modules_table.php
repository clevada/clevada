<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_modules')) {

            Schema::create('sys_modules', function (Blueprint $table) {
                $table->id();
                $table->string('module', 50);
                $table->string('label', 100);
                $table->string('status', 25);
                $table->string('route_web', 100)->nullable();
                $table->string('route_admin', 100)->nullable();
                $table->tinyInteger('hidden')->default(0);
            });
        } else {

            Schema::table('sys_modules', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_modules', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_modules', 'module'))
                    $table->string('module', 50);

                if (!Schema::hasColumn('sys_modules', 'label'))
                    $table->string('label', 100);

                if (!Schema::hasColumn('sys_modules', 'status'))
                    $table->string('status', 25);

                if (!Schema::hasColumn('sys_modules', 'route_web'))
                    $table->string('route_web', 100)->nullable();

                if (!Schema::hasColumn('sys_modules', 'route_admin'))
                    $table->string('route_admin', 100)->nullable();

                if (!Schema::hasColumn('sys_modules', 'hidden'))
                    $table->tinyInteger('hidden')->default(0);
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
        Schema::dropIfExists('sys_modules');
    }
}
