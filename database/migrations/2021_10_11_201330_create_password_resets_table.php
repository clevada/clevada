<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('password_resets')) {

            Schema::create('password_resets', function (Blueprint $table) {
                $table->string('email', 255)->index();
                $table->string('token', 255);
                $table->timestamp('created_at')->nullable();
            });
        
        } else {

            Schema::table('password_resets', function (Blueprint $table) {

                if (! Schema::hasColumn('password_resets', 'email')) 
                    $table->string('email', 255)->index();
                
                if (! Schema::hasColumn('password_resets', 'token')) 
                    $table->string('token', 255);
                
                if (! Schema::hasColumn('password_resets', 'created_at')) 
                    $table->timestamp('created_at')->nullable();                
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
        Schema::dropIfExists('password_resets');
    }
}
