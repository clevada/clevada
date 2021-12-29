<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('forum_attachments')) {

            Schema::create('forum_attachments', function (Blueprint $table) {
                $table->id();
                $table->integer('topic_id');
                $table->integer('post_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->string('file', 255);
            });
        
        } else {

            Schema::table('forum_attachments', function (Blueprint $table) {

                if (! Schema::hasColumn('forum_attachments', 'id')) 
                    $table->id();

                if (! Schema::hasColumn('forum_attachments', 'topic_id')) 
                    $table->integer('topic_id');
                
                if (! Schema::hasColumn('forum_attachments', 'post_id')) 
                    $table->integer('post_id')->nullable();
                
                if (! Schema::hasColumn('forum_attachments', 'user_id')) 
                    $table->integer('user_id')->nullable();
                
                if (! Schema::hasColumn('forum_attachments', 'file')) 
                    $table->string('file', 255);
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
        Schema::dropIfExists('forum_attachments');
    }
}
