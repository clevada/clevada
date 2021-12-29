<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumUserWarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('forum_user_warnings')) {

            Schema::create('forum_user_warnings', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('restricted_by_user_id');
                $table->integer('topic_id')->nullable();
                $table->integer('post_id')->nullable();
                $table->text('warning')->nullable();
                $table->integer('report_id')->nullable();
                $table->timestamps();
            });
        
        } else {

            Schema::table('forum_user_warnings', function (Blueprint $table) {

                if (! Schema::hasColumn('forum_user_warnings', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('forum_user_warnings', 'user_id')) 
                    $table->integer('user_id');
                
                if (! Schema::hasColumn('forum_user_warnings', 'restricted_by_user_id')) 
                    $table->integer('restricted_by_user_id');
                
                if (! Schema::hasColumn('forum_user_warnings', 'topic_id')) 
                    $table->integer('topic_id')->nullable();
                
                if (! Schema::hasColumn('forum_user_warnings', 'post_id')) 
                    $table->integer('post_id')->nullable();                    
                
                if (! Schema::hasColumn('forum_user_warnings', 'warning')) 
                    $table->text('warning')->nullable();
                
                if (! Schema::hasColumn('forum_user_warnings', 'report_id')) 
                    $table->integer('report_id')->nullable();
                
                if (! Schema::hasColumn('forum_user_warnings', 'id')) 
                    $table->timestamps();
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
        Schema::dropIfExists('forum_user_warnings');
    }
}
