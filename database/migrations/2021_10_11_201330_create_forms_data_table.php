<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forms_data')) {

            Schema::create('forms_data', function (Blueprint $table) {
                $table->id();
                $table->integer('form_id');
                $table->integer('source_lang_id')->nullable();
                $table->integer('task_id')->nullable();
                $table->integer('project_id')->nullable();
                $table->integer('lead_id')->nullable();
                $table->string('name', 250)->nullable();
                $table->string('email', 250)->nullable();
                $table->text('subject')->nullable();
                $table->mediumText('message')->nullable();
                $table->string('ip', 50)->nullable();
                $table->text('referer')->nullable();
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                $table->timestamp('read_at')->nullable();
                $table->timestamp('responded_at')->nullable();
                $table->tinyInteger('is_important')->default(0);
                $table->tinyInteger('is_spam')->default(0);
                $table->timestamp('deleted_at')->nullable();
            });
        } else {

            Schema::table('forms_data', function (Blueprint $table) {

                if (!Schema::hasColumn('forms_data', 'id'))
                    $table->id();

                if (!Schema::hasColumn('forms_data', 'form_id'))
                    $table->integer('form_id');

                if (!Schema::hasColumn('forms_data', 'source_lang_id'))
                    $table->integer('source_lang_id')->nullable();

                if (!Schema::hasColumn('forms_data', 'task_id'))
                    $table->integer('task_id')->nullable();

                if (!Schema::hasColumn('forms_data', 'project_id'))
                    $table->integer('project_id')->nullable();

                if (!Schema::hasColumn('forms_data', 'lead_id'))
                    $table->integer('lead_id')->nullable();

                if (!Schema::hasColumn('forms_data', 'name'))
                    $table->string('name', 250)->nullable();

                if (!Schema::hasColumn('forms_data', 'email'))
                    $table->string('email', 250)->nullable();

                if (!Schema::hasColumn('forms_data', 'subject'))
                    $table->text('subject')->nullable();

                if (!Schema::hasColumn('forms_data', 'message'))
                    $table->mediumText('message')->nullable();

                if (!Schema::hasColumn('forms_data', 'ip'))
                    $table->string('ip', 50)->nullable();

                if (!Schema::hasColumn('forms_data', 'referer'))
                    $table->text('referer')->nullable();

                if (!Schema::hasColumn('forms_data', 'created_at'))
                    $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();

                if (!Schema::hasColumn('forms_data', 'read_at'))
                    $table->timestamp('read_at')->nullable();

                if (!Schema::hasColumn('forms_data', 'responded_at'))
                    $table->timestamp('responded_at')->nullable();

                if (!Schema::hasColumn('forms_data', 'is_important'))
                    $table->tinyInteger('is_important')->default(0);

                if (!Schema::hasColumn('forms_data', 'is_spam'))
                    $table->tinyInteger('is_spam')->nullable();

                if (!Schema::hasColumn('forms_data', 'deleted_at'))
                    $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('forms_data');
    }
}
