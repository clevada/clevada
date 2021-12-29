<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forms_fields')) {

            Schema::create('forms_fields', function (Blueprint $table) {
                $table->id();
                $table->integer('form_id');
                $table->string('type', 250);
                $table->tinyInteger('required')->default(0);
                $table->tinyInteger('col_md')->default(6);
                $table->tinyInteger('active')->default(0);
                $table->integer('position')->default(0);
                $table->tinyInteger('protected')->default(0);
                $table->tinyInteger('is_default_name')->default(0);
                $table->tinyInteger('is_default_email')->default(0);
                $table->tinyInteger('is_default_subject')->default(0);
                $table->tinyInteger('is_default_message')->default(0);
            });
        } else {

            Schema::table('forms_fields', function (Blueprint $table) {
                if (!Schema::hasColumn('forms_fields', 'id'))
                    $table->id();

                if (!Schema::hasColumn('forms_fields', 'form_id'))
                    $table->integer('form_id');

                if (!Schema::hasColumn('forms_fields', 'type'))
                    $table->string('type', 250);

                if (!Schema::hasColumn('forms_fields', 'required'))
                    $table->tinyInteger('required')->default(0);

                if (!Schema::hasColumn('forms_fields', 'col_md'))
                    $table->tinyInteger('col_md')->default(6);

                if (!Schema::hasColumn('forms_fields', 'active'))
                    $table->tinyInteger('active')->default(0);

                if (!Schema::hasColumn('forms_fields', 'position'))
                    $table->integer('position')->default(0);

                if (!Schema::hasColumn('forms_fields', 'protected'))
                    $table->tinyInteger('protected')->default(0);

                if (!Schema::hasColumn('forms_fields', 'is_default_name'))
                    $table->tinyInteger('is_default_name')->default(0);

                if (!Schema::hasColumn('forms_fields', 'is_default_email'))
                    $table->tinyInteger('is_default_email')->default(0);

                if (!Schema::hasColumn('forms_fields', 'is_default_subject'))
                    $table->tinyInteger('is_default_subject')->default(0);

                if (!Schema::hasColumn('forms_fields', 'is_default_message'))
                    $table->tinyInteger('is_default_message')->default(0);
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
        Schema::dropIfExists('forms_fields');
    }
}
