<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('forms')) {
            
            Schema::create('forms', function (Blueprint $table) {
                $table->id();
                $table->string('label', 250)->nullable();
                $table->tinyInteger('active')->default(0);
                $table->tinyInteger('recaptcha')->default(0);
            });
        } else {

            Schema::table('forms', function (Blueprint $table) {
                if (!Schema::hasColumn('forms', 'id'))
                    $table->id();

                if (!Schema::hasColumn('forms', 'label'))
                    $table->string('label', 250)->nullable();

                if (!Schema::hasColumn('forms', 'active'))
                    $table->tinyInteger('active')->default(0);

                if (!Schema::hasColumn('forms', 'recaptcha'))
                    $table->tinyInteger('recaptcha')->nullable();
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
        Schema::dropIfExists('forms');
    }
}
