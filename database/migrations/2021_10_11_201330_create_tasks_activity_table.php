<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tasks_activity')) {

            Schema::create('tasks_activity', function (Blueprint $table) {
                $table->id();
                $table->integer('task_id');
                $table->integer('user_id');
                $table->string('type', 50)->nullable();
                $table->mediumtext('message')->nullable();
                $table->text('file')->nullable();
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                $table->tinyInteger('is_important')->default(0);
                $table->tinyInteger('internal_only')->default(0);
                $table->tinyInteger('progress_old')->nullable();
                $table->tinyInteger('progress_new')->nullable();
            });
        } else {

            Schema::table('tasks_activity', function (Blueprint $table) {

                if (!Schema::hasColumn('tasks_activity', 'id'))
                    $table->id();

                if (!Schema::hasColumn('tasks_activity', 'task_id'))
                    $table->integer('task_id');

                if (!Schema::hasColumn('tasks_activity', 'user_id'))
                    $table->integer('user_id');

                if (!Schema::hasColumn('tasks_activity', 'type'))
                    $table->string('type', 50)->nullable();

                if (!Schema::hasColumn('tasks_activity', 'message'))
                    $table->mediumtext('message')->nullable();

                if (!Schema::hasColumn('tasks_activity', 'file'))
                    $table->text('file')->nullable();

                if (!Schema::hasColumn('tasks_activity', 'created_at'))
                    $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();

                if (!Schema::hasColumn('tasks_activity', 'is_important'))
                    $table->tinyInteger('is_important')->default(0);

                if (!Schema::hasColumn('tasks_activity', 'internal_only'))
                    $table->tinyInteger('internal_only')->default(0);

                if (!Schema::hasColumn('tasks_activity', 'progress_old'))
                    $table->tinyInteger('progress_old')->nullable();

                if (!Schema::hasColumn('tasks_activity', 'progress_new'))
                    $table->tinyInteger('progress_new')->nullable();
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
        Schema::dropIfExists('tasks_activity');
    }
}
