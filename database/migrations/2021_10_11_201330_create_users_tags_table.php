<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('users_tags')) {

            Schema::create('users_tags', function (Blueprint $table) {
                $table->id();
                $table->smallInteger('role_id')->nullable();
                $table->string('tag', 100);
                $table->string('color', 50)->nullable();
            });

        } else {

            Schema::table('users_tags', function (Blueprint $table) {

                if (! Schema::hasColumn('users_tags', 'id')) 
                    $table->id();
                    
                if (! Schema::hasColumn('users_tags', 'role_id')) 
                    $table->smallInteger('role_id')->nullable();
                
                if (! Schema::hasColumn('users_tags', 'tag')) 
                    $table->string('tag', 100);
                
                if (! Schema::hasColumn('users_tags', 'color')) 
                    $table->string('color', 50)->nullable();
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
        Schema::dropIfExists('users_tags');
    }
}
