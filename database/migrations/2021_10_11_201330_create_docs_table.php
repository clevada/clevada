<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('docs')) {

            Schema::create('docs', function (Blueprint $table) {
                $table->id();
                $table->string('label', 200)->nullable();
                $table->integer('user_id')->nullable();
                $table->smallInteger('categ_id')->nullable();
                $table->tinyInteger('active');
                $table->smallInteger('position')->nullable();
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                $table->tinyInteger('featured')->default(0);
                $table->string('visibility', 25)->nullable();
                $table->mediumText('blocks')->nullable();
            });
        } else {

            Schema::table('docs', function (Blueprint $table) {

                if (!Schema::hasColumn('docs', 'id'))
                    $table->id();

                if (!Schema::hasColumn('docs', 'label'))
                    $table->string('label', 200)->nullable();

                if (!Schema::hasColumn('docs', 'user_id'))
                    $table->integer('user_id')->nullable();

                if (!Schema::hasColumn('docs', 'categ_id'))
                    $table->smallInteger('categ_id')->nullable();

                if (!Schema::hasColumn('docs', 'active'))
                    $table->tinyInteger('active');

                if (!Schema::hasColumn('docs', 'position'))
                    $table->smallInteger('position')->nullable();

                if (!Schema::hasColumn('docs', 'created_at'))
                    $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();

                if (!Schema::hasColumn('docs', 'featured'))
                    $table->tinyInteger('featured')->default(0);

                if (!Schema::hasColumn('docs', 'visibility'))
                    $table->string('visibility', 25)->nullable();

                if (!Schema::hasColumn('docs', 'blocks'))
                    $table->mediumText('blocks')->nullable();
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
        Schema::dropIfExists('docs');
    }
}
