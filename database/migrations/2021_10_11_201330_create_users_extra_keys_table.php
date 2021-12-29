<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersExtraKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('users_extra_keys')) {

            Schema::create('users_extra_keys', function (Blueprint $table) {
                $table->id();
                $table->string('extra_key', 100);
            });

        } else {

            Schema::table('users_extra_keys', function (Blueprint $table) {

                if (! Schema::hasColumn('users_extra_keys', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('users_extra_keys', 'extra_key')) 
                    $table->string('extra_key', 100);
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
        Schema::dropIfExists('users_extra_keys');
    }
}
