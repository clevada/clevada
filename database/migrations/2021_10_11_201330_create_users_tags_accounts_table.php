<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTagsAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('users_tags_accounts')) {

            Schema::create('users_tags_accounts', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('tag_id');
            });
        
        } else {

            Schema::table('users_tags_accounts', function (Blueprint $table) {

                if (! Schema::hasColumn('users_tags_accounts', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('users_tags_accounts', 'user_id')) 
                    $table->integer('user_id');
                
                if (! Schema::hasColumn('users_tags_accounts', 'tag_id')) 
                    $table->integer('tag_id');
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
        Schema::dropIfExists('users_tags_accounts');
    }
}
