<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('log_email')) {

            Schema::create('log_email', function (Blueprint $table) {
                $table->id();
                $table->string('module', 100)->nullable();
                $table->string('type', 100)->nullable();
                $table->integer('item_id')->nullable();
                $table->integer('to_user_id')->nullable();
                $table->string('email', 200);
                $table->text('subject');
                $table->mediumtext('message')->nullable();
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            });
        } else {

            Schema::table('log_email', function (Blueprint $table) {

                if (!Schema::hasColumn('log_email', 'id'))
                    $table->id();

                if (!Schema::hasColumn('log_email', 'module'))
                    $table->string('module', 100)->nullable();

                if (!Schema::hasColumn('log_email', 'type'))
                    $table->string('type', 100)->nullable();

                if (!Schema::hasColumn('log_email', 'item_id'))
                    $table->integer('item_id')->nullable();

                if (!Schema::hasColumn('log_email', 'to_user_id'))
                    $table->integer('to_user_id')->nullable();

                if (!Schema::hasColumn('log_email', 'email'))
                    $table->string('email', 200)->nullable();

                if (!Schema::hasColumn('log_email', 'subject'))
                    $table->text('subject')->nullable();

                if (!Schema::hasColumn('log_email', 'message'))
                    $table->mediumtext('message')->nullable();

                if (!Schema::hasColumn('log_email', 'created_at'))
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
        Schema::dropIfExists('log_email');
    }
}
