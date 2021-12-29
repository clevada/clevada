<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsDataReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forms_data_reply')) {

            Schema::create('forms_data_reply', function (Blueprint $table) {
                $table->id();
                $table->integer('form_id');
                $table->integer('form_data_id');
                $table->integer('from_user_id');
                $table->mediumText('message');
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            });
        } else {

            Schema::table('forms_data_reply', function (Blueprint $table) {

                if (!Schema::hasColumn('forms_data_reply', 'id'))
                    $table->id();

                if (!Schema::hasColumn('forms_data_reply', 'form_id'))
                    $table->integer('form_id');

                if (!Schema::hasColumn('forms_data_reply', 'form_data_id'))
                    $table->integer('form_data_id');

                if (!Schema::hasColumn('forms_data_reply', 'from_user_id'))
                    $table->integer('from_user_id');

                if (!Schema::hasColumn('forms_data_reply', 'message'))
                    $table->mediumText('message');

                if (!Schema::hasColumn('forms_data_reply', 'created_at'))
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
        Schema::dropIfExists('forms_data_reply');
    }
}
