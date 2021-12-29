<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_permissions')) {

            Schema::create('sys_permissions', function (Blueprint $table) {
                $table->id();
                $table->string('module', 100);
                $table->string('label', 100);
                $table->smallInteger('position')->nullable();
                $table->string('permission', 100);
                $table->text('description')->nullable();
            });
        } else {

            Schema::table('sys_permissions', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_permissions', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_permissions', 'module'))
                    $table->string('module', 100);

                if (!Schema::hasColumn('sys_permissions', 'label'))
                    $table->string('label', 100);

                if (!Schema::hasColumn('sys_permissions', 'position'))
                    $table->smallInteger('position')->nullable();

                if (!Schema::hasColumn('sys_permissions', 'permission'))
                    $table->string('permission', 100);

                if (!Schema::hasColumn('sys_permissions', 'description'))
                    $table->text('description')->nullable();
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
        Schema::dropIfExists('sys_permissions');
    }
}
