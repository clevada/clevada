<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersInternalNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users_internal_notes')) {

            Schema::create('users_internal_notes', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('created_by_user_id');
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                $table->text('note')->nullable();
                $table->smallInteger('sticky')->default(0);
                $table->text('file')->nullable();
            });
        } else {

            Schema::table('users_internal_notes', function (Blueprint $table) {
                if (!Schema::hasColumn('users_internal_notes', 'id'))
                    $table->id();

                if (!Schema::hasColumn('users_internal_notes', 'user_id'))
                    $table->integer('user_id');

                if (!Schema::hasColumn('users_internal_notes', 'created_by_user_id'))
                    $table->integer('created_by_user_id');

                if (!Schema::hasColumn('users_internal_notes', 'created_at'))
                    $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();

                if (!Schema::hasColumn('users_internal_notes', 'note'))
                    $table->text('note')->nullable();

                if (!Schema::hasColumn('users_internal_notes', 'sticky'))
                    $table->smallInteger('sticky')->default(0);

                if (!Schema::hasColumn('users_internal_notes', 'file'))
                    $table->text('file')->nullable();
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
        Schema::dropIfExists('users_internal_notes');
    }
}
