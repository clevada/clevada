<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('blocks')) {

            Schema::create('blocks', function (Blueprint $table) {
                $table->id();
                $table->integer('type_id');
                $table->string('label', 200)->nullable();
                $table->text('extra')->nullable();
                $table->string('module', 100)->nullable();
                $table->integer('content_id')->nullable();
                $table->integer('template_id')->nullable();
                $table->integer('position')->default(0);
                $table->tinyInteger('hide')->default(0);
                $table->integer('created_by_user_id');
                $table->integer('updated_by_user_id')->nullable();
                $table->mediumtext('custom_css')->nullable();
                $table->timestamps();
            });
        } else {

            Schema::table('blocks', function (Blueprint $table) {

                if (!Schema::hasColumn('blocks', 'id'))
                    $table->id();

                if (!Schema::hasColumn('blocks', 'type_id'))
                    $table->integer('type_id');

                if (!Schema::hasColumn('blocks', 'label'))
                    $table->string('label', 200)->nullable();

                if (!Schema::hasColumn('blocks', 'extra'))
                    $table->text('extra')->nullable();

                if (!Schema::hasColumn('blocks', 'module'))
                    $table->string('module', 100)->nullable();

                if (!Schema::hasColumn('blocks', 'content_id'))
                    $table->integer('content_id')->nullable();

                if (!Schema::hasColumn('blocks', 'template_id'))
                    $table->integer('template_id')->nullable();

                if (!Schema::hasColumn('blocks', 'position'))
                    $table->integer('position')->default(0);

                if (!Schema::hasColumn('blocks', 'hide'))
                    $table->tinyInteger('hide')->default(0);

                if (!Schema::hasColumn('blocks', 'created_by_user_id'))
                    $table->integer('created_by_user_id');

                if (!Schema::hasColumn('blocks', 'updated_by_user_id'))
                    $table->integer('updated_by_user_id')->nullable();

                if (!Schema::hasColumn('blocks', 'custom_css'))
                    $table->mediumtext('custom_css')->nullable();

                if (!Schema::hasColumn('blocks', 'id'))
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
        Schema::dropIfExists('blocks');
    }
}
