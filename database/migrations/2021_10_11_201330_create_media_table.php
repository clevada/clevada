<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('media')) {

            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->text('file')->nullable();
                $table->integer('created_by_user_id')->nullable();
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            });
        } else {

            Schema::table('media', function (Blueprint $table) {

                if (!Schema::hasColumn('media', 'id'))
                    $table->id();

                if (!Schema::hasColumn('media', 'file'))
                    $table->text('file')->nullable();

                if (!Schema::hasColumn('media', 'created_by_user_id'))
                    $table->integer('created_by_user_id')->nullable();

                if (!Schema::hasColumn('media', 'created_at'))
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
        Schema::dropIfExists('media');
    }
}
