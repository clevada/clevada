<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsFieldsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forms_fields_data')) {

            Schema::create('forms_fields_data', function (Blueprint $table) {
                $table->id();
                $table->integer('form_id');
                $table->integer('form_data_id');
                $table->integer('field_id');
                $table->mediumText('value')->nullable();
            });
        } else {

            Schema::table('forms_fields_data', function (Blueprint $table) {

                if (!Schema::hasColumn('forms_fields_data', 'id'))
                    $table->id();

                if (!Schema::hasColumn('forms_fields_data', 'form_id'))
                    $table->integer('form_id');

                if (!Schema::hasColumn('forms_fields_data', 'form_data_id'))
                    $table->integer('form_data_id');

                if (!Schema::hasColumn('forms_fields_data', 'field_id'))
                    $table->integer('field_id');
            
                if (!Schema::hasColumn('forms_fields_data', 'value'))
                    $table->mediumText('value')->nullable();
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
        Schema::dropIfExists('forms_fields_data');
    }
}
