<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('blocks_types')) {

            Schema::create('blocks_types', function (Blueprint $table) {
                $table->id();
                $table->string('label', 100);
                $table->string('type', 100);
                $table->string('icon', 200)->nullable();
                $table->smallInteger('position')->default(0);
                $table->tinyInteger('allow_footer')->default(0);
                $table->tinyInteger('allow_to_users')->default(0);
            });
        } else {

            Schema::table('blocks_types', function (Blueprint $table) {

                if (!Schema::hasColumn('blocks_types', 'id'))
                    $table->id();

                if (!Schema::hasColumn('blocks_types', 'label'))
                    $table->string('label', 100);

                if (!Schema::hasColumn('blocks_types', 'type'))
                    $table->string('type', 100);

                if (!Schema::hasColumn('blocks_types', 'icon'))
                    $table->string('icon', 100)->nullable();

                if (!Schema::hasColumn('blocks_types', 'position'))
                    $table->smallInteger('position')->default(0);

                if (!Schema::hasColumn('blocks_types', 'allow_footer'))
                    $table->tinyInteger('allow_footer')->default(0);

                if (!Schema::hasColumn('blocks_types', 'allow_to_users'))
                    $table->tinyInteger('allow_to_users')->default(0);
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
        Schema::dropIfExists('blocks_types');
    }
}
