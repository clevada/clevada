<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pages')) {

            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->integer('parent_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->string('label', 100);
                $table->string('sidebar_position', 50)->nullable();
                $table->integer('sidebar_id')->nullable();
                $table->integer('top_section_id')->nullable();
                $table->integer('bottom_section_id')->nullable();
                $table->tinyInteger('active')->default(0);
                $table->mediumtext('blocks')->nullable();
                $table->timestamps();
            });
        } else {

            Schema::table('pages', function (Blueprint $table) {

                if (!Schema::hasColumn('pages', 'id'))
                    $table->id();

                if (!Schema::hasColumn('pages', 'parent_id'))
                    $table->integer('parent_id')->nullable();

                if (!Schema::hasColumn('pages', 'user_id'))
                    $table->integer('user_id')->nullable();

                if (!Schema::hasColumn('pages', 'label'))
                    $table->string('label', 100);

                if (!Schema::hasColumn('pages', 'sidebar_position'))
                    $table->string('sidebar_position', 50)->nullable();

                if (!Schema::hasColumn('pages', 'sidebar_id'))
                    $table->integer('sidebar_id')->nullable();

                if (!Schema::hasColumn('pages', 'top_section_id'))
                    $table->integer('top_section_id')->nullable();

                if (!Schema::hasColumn('pages', 'bottom_section_id'))
                    $table->integer('bottom_section_id')->nullable();

                if (!Schema::hasColumn('pages', 'active'))
                    $table->tinyInteger('active')->default(0);

                if (!Schema::hasColumn('pages', 'blocks'))
                    $table->mediumtext('blocks')->nullable();

                if (!Schema::hasColumn('pages', 'id'))
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
        Schema::dropIfExists('pages');
    }
}
