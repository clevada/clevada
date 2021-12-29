<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('forum_reports')) {

            Schema::create('forum_reports', function (Blueprint $table) {
                $table->id();
                $table->integer('from_user_id');
                $table->integer('to_user_id')->nullable();
                $table->integer('topic_id')->nullable();
                $table->integer('post_id')->nullable();
                $table->text('reason')->nullable();
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                $table->timestamp('processed_at')->nullable();
            });
        
        } else {

            Schema::table('forum_reports', function (Blueprint $table) {

                if (! Schema::hasColumn('forum_reports', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('forum_reports', 'from_user_id')) 
                    $table->integer('from_user_id');
                
                if (! Schema::hasColumn('forum_reports', 'to_user_id')) 
                    $table->integer('to_user_id')->nullable();
                
                if (! Schema::hasColumn('forum_reports', 'topic_id')) 
                    $table->integer('topic_id')->nullable();
                
                if (! Schema::hasColumn('forum_reports', 'post_id')) 
                    $table->integer('post_id')->nullable();
                
                if (! Schema::hasColumn('forum_reports', 'reason')) 
                    $table->text('reason')->nullable();
                
                if (! Schema::hasColumn('forum_reports', 'created_at')) 
                    $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                
                if (! Schema::hasColumn('forum_reports', 'processed_at')) 
                    $table->timestamp('processed_at')->nullable();                    
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
        Schema::dropIfExists('forum_reports');
    }
}
