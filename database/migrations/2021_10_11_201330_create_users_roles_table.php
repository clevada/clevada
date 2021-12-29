<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('users_roles')) {

            Schema::create('users_roles', function (Blueprint $table) {
                $table->id();
                $table->string('role', 100);
                $table->tinyInteger('active');
                $table->tinyInteger('registration_enabled')->default(0);
            });
        } else {

            Schema::table('users_roles', function (Blueprint $table) {

                if (! Schema::hasColumn('users_roles', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('users_roles', 'role'))                     
                    $table->string('role', 100);
                
                if (! Schema::hasColumn('users_roles', 'active')) 
                    $table->tinyInteger('active');
                
                if (! Schema::hasColumn('users_roles', 'registration_enabled')) 
                    $table->tinyInteger('registration_enabled')->default(0);
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
        Schema::dropIfExists('users_roles');
    }
}
