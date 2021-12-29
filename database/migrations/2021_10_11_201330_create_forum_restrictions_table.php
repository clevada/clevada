<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('forum_restrictions')) {

            Schema::create('forum_restrictions', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('restricted_by_user_id')->nullable();
                $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                $table->integer('report_id')->nullable();
                $table->smallInteger('deny_topic_create_days')->nullable()->default(0);
                $table->date('deny_topic_create_expire_at')->nullable();
                $table->smallInteger('deny_post_create_days')->nullable()->default(0);
                $table->date('deny_post_create_expire_at')->nullable();
            });
        
        } else {

            Schema::table('forum_restrictions', function (Blueprint $table) {

                if (! Schema::hasColumn('forum_restrictions', 'id')) 
                    $table->id();
            
                if (! Schema::hasColumn('forum_restrictions', 'user_id')) 
                    $table->integer('user_id');
                
                if (! Schema::hasColumn('forum_restrictions', 'restricted_by_user_id')) 
                    $table->integer('restricted_by_user_id')->nullable();
                
                if (! Schema::hasColumn('forum_restrictions', 'created_at')) 
                    $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
                
                if (! Schema::hasColumn('forum_restrictions', 'report_id')) 
                    $table->integer('report_id')->nullable();
                
                if (! Schema::hasColumn('forum_restrictions', 'deny_topic_create_days')) 
                    $table->smallInteger('deny_topic_create_days')->nullable()->default(0);
                
                if (! Schema::hasColumn('forum_restrictions', 'deny_topic_create_expire_at')) 
                    $table->date('deny_topic_create_expire_at')->nullable();
                
                if (! Schema::hasColumn('forum_restrictions', 'deny_post_create_days')) 
                    $table->smallInteger('deny_post_create_days')->nullable()->default(0);
                
                if (! Schema::hasColumn('forum_restrictions', 'deny_post_create_expire_at')) 
                    $table->date('deny_post_create_expire_at')->nullable();
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
        Schema::dropIfExists('forum_restrictions');
    }
}
