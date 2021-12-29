<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysTemplatesConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('sys_templates_config')) {

            Schema::create('sys_templates_config', function (Blueprint $table) {
                $table->id();
                $table->integer('template_id');
                $table->string('name', 250);
                $table->text('value')->nullable();
            });

        } else {

            Schema::table('sys_templates_config', function (Blueprint $table) {

                if (! Schema::hasColumn('sys_templates_config', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('sys_templates_config', 'template_id')) 
                    $table->integer('template_id');

                if (! Schema::hasColumn('sys_templates_config', 'name')) 
                    $table->string('name', 250);

                if (! Schema::hasColumn('sys_templates_config', 'value')) 
                    $table->text('value')->nullable();
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
        Schema::dropIfExists('sys_templates_config');
    }
}
