<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsFieldsLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forms_fields_lang')) {

            Schema::create('forms_fields_lang', function (Blueprint $table) {
                $table->id();
                $table->integer('form_id');
                $table->integer('field_id');
                $table->integer('lang_id');
                $table->text('label');
                $table->text('info')->nullable();
                $table->text('dropdowns')->nullable();
            });
        } else {

            Schema::table('forms_fields_lang', function (Blueprint $table) {
                if (!Schema::hasColumn('forms_fields_lang', 'id'))
                    $table->id();

                if (!Schema::hasColumn('forms_fields_lang', 'form_id'))
                    $table->integer('form_id');

                if (!Schema::hasColumn('forms_fields_lang', 'field_id'))
                    $table->integer('field_id');

                if (!Schema::hasColumn('forms_fields_lang', 'lang_id'))
                    $table->integer('lang_id');

                if (!Schema::hasColumn('forms_fields_lang', 'label'))
                    $table->text('label');

                if (!Schema::hasColumn('forms_fields_lang', 'info'))
                    $table->text('info')->nullable();

                if (!Schema::hasColumn('forms_fields_lang', 'dropdowns'))
                    $table->text('dropdowns')->nullable();
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
        Schema::dropIfExists('forms_fields_lang');
    }
}
