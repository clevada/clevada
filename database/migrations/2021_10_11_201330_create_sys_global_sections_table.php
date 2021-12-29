<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysGlobalSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_global_sections')) {

            Schema::create('sys_global_sections', function (Blueprint $table) {
                $table->id();
                $table->string('label', 100);
            });
        } else {

            Schema::table('sys_global_sections', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_global_sections', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_global_sections', 'label'))
                    $table->string('label', 100);             
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
        Schema::dropIfExists('sys_global_sections');
    }
}
