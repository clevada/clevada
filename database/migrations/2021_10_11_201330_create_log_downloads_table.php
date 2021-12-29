<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('log_downloads')) {

            Schema::create('log_downloads', function (Blueprint $table) {
                $table->id();
                $table->integer('block_id')->nullable();                
                $table->integer('user_id')->nullable();
                $table->text('file')->nullable();
                $table->string('ip', 100)->nullable();
                $table->integer('filesize')->nullable();
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            });
        
        } else {

            Schema::table('log_downloads', function (Blueprint $table) {

                if (! Schema::hasColumn('log_downloads', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('log_downloads', 'block_id')) 
                    $table->integer('block_id')->nullable();                         
                
                if (! Schema::hasColumn('log_downloads', 'user_id')) 
                    $table->integer('user_id')->nullable();
             
                if (! Schema::hasColumn('log_downloads', 'file')) 
                    $table->text('file')->nullable();
                
                if (! Schema::hasColumn('log_downloads', 'ip')) 
                    $table->string('ip', 100)->nullable();
                
                if (! Schema::hasColumn('log_downloads', 'filesize')) 
                    $table->integer('filesize')->nullable();
             
                if (! Schema::hasColumn('log_downloads', 'created_at')) 
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
        Schema::dropIfExists('log_downloads');
    }
}
