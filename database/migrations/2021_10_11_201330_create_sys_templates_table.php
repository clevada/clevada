<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sys_templates')) {

            Schema::create('sys_templates', function (Blueprint $table) {
                $table->id();
                $table->string('label', 100);
                $table->tinyInteger('is_default')->default(0);
                $table->tinyInteger('is_builder')->default(0);
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            });
        } else {

            Schema::table('sys_templates', function (Blueprint $table) {

                if (!Schema::hasColumn('sys_templates', 'id'))
                    $table->id();

                if (!Schema::hasColumn('sys_templates', 'label'))
                    $table->string('label', 100);

                if (!Schema::hasColumn('sys_templates', 'is_default'))
                    $table->tinyInteger('is_default')->default(0);

                if (!Schema::hasColumn('sys_templates', 'is_builder'))
                    $table->tinyInteger('is_builder')->default(0);

                if (!Schema::hasColumn('sys_templates', 'created_at'))
                    $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
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
        Schema::dropIfExists('sys_templates');
    }
}
