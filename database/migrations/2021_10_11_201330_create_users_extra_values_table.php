<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersExtraValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('users_extra_values')) {

            Schema::create('users_extra_values', function (Blueprint $table) {
                $table->id();
                $table->integer('key_id');
                $table->integer('user_id');
                $table->longText('value')->nullable();
            });
        
        } else {

            Schema::table('users_extra_values', function (Blueprint $table) {

                if (! Schema::hasColumn('users_extra_values', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('users_extra_values', 'key_id')) 
                    $table->integer('key_id');
                
                if (! Schema::hasColumn('users_extra_values', 'user_id')) 
                    $table->integer('user_id');
                
                if (! Schema::hasColumn('users_extra_values', 'value')) 
                    $table->longText('value')->nullable();

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
        Schema::dropIfExists('users_extra_values');
    }
}
