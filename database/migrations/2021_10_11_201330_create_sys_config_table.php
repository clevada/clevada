<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('sys_config')) {

            Schema::create('sys_config', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150)->unique('name');
                $table->longText('value')->nullable();
                $table->tinyInteger('is_custom')->default(0);
            });

        } else {

            Schema::table('sys_config', function (Blueprint $table) {

                if (! Schema::hasColumn('sys_config', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('sys_config', 'name')) 
                    $table->string('name', 150)->unique('name');
                
                if (! Schema::hasColumn('sys_config', 'value')) 
                    $table->longText('value')->nullable();
                
                if (! Schema::hasColumn('sys_config', 'is_custom')) 
                    $table->tinyInteger('is_custom')->default(0);
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
        Schema::dropIfExists('sys_config');
    }
}
