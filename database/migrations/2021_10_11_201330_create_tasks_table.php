<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tasks')) {

            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->text('title');
                $table->mediumtext('description')->nullable();
                $table->integer('client_user_id')->nullable();
                $table->integer('operator_user_id')->nullable();
                $table->integer('created_by_user_id')->nullable();
                $table->integer('form_data_id')->nullable();
                $table->tinyInteger('priority')->default(2);
                $table->date('due_date')->nullable();
                $table->timestamps();
                $table->timestamp('closed_at');
                $table->tinyInteger('share_access')->default(0);
                $table->string('access_token', 220)->nullable();
                $table->tinyInteger('show_share_progress')->default(1);
                $table->tinyInteger('share_disable_names')->default(0);
                $table->tinyInteger('progress')->default(0);
            });
        } else {

            Schema::table('tasks', function (Blueprint $table) {

                if (!Schema::hasColumn('tasks', 'id'))
                    $table->id();

                if (!Schema::hasColumn('tasks', 'title'))
                    $table->text('title');

                if (!Schema::hasColumn('tasks', 'description'))
                    $table->mediumtext('description')->nullable();

                if (!Schema::hasColumn('tasks', 'client_user_id'))
                    $table->integer('client_user_id')->nullable();

                if (!Schema::hasColumn('tasks', 'operator_user_id'))
                    $table->integer('operator_user_id')->nullable();

                if (!Schema::hasColumn('tasks', 'created_by_user_id'))
                    $table->integer('created_by_user_id')->nullable();

                if (!Schema::hasColumn('tasks', 'form_data_id'))
                    $table->integer('form_data_id')->nullable();

                if (!Schema::hasColumn('tasks', 'priority'))
                    $table->tinyInteger('priority')->default(2);

                if (!Schema::hasColumn('tasks', 'due_date'))
                    $table->date('due_date')->nullable();

                if (!Schema::hasColumn('tasks', 'id'))
                    $table->timestamps();

                if (!Schema::hasColumn('tasks', 'closed_at'))
                    $table->timestamp('closed_at');

                if (!Schema::hasColumn('tasks', 'share_access'))
                    $table->tinyInteger('share_access')->default(0);

                if (!Schema::hasColumn('tasks', 'access_token'))
                    $table->string('access_token', 220)->nullable();

                if (!Schema::hasColumn('tasks', 'show_share_progress'))
                    $table->tinyInteger('show_share_progress')->default(1);

                if (!Schema::hasColumn('tasks', 'share_disable_names'))
                    $table->tinyInteger('share_disable_names')->default(0);

                if (!Schema::hasColumn('tasks', 'progress'))
                    $table->tinyInteger('progress')->default(0);
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
        Schema::dropIfExists('tasks');
    }
}
