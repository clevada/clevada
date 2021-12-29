<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysSidebarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_sidebars')) {

            Schema::create('sys_sidebars', function (Blueprint $table) {
                $table->id();
                $table->string('label', 100);
            });
        } else {

            Schema::table('sys_sidebars', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_sidebars', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_sidebars', 'label'))
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
        Schema::dropIfExists('sys_sidebars');
    }
}
