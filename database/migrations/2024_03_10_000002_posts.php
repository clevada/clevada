<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->char('type', 25);
            $table->string('title', 250)->index('title');
            $table->string('slug', 250);
            $table->string('image', 250)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status', 20);
            $table->text('summary')->nullable();
            $table->mediumText('content')->nullable();
            $table->text('tags')->nullable();
            $table->tinyInteger('featured')->nullable();
            $table->string('meta_title', 250)->nullable();
            $table->string('meta_description', 250)->nullable();
            $table->integer('hits')->default(0);
            $table->smallInteger('minutes_to_read')->nullable();
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->text('search_terms')->nullable();
            $table->mediumtext('blocks')->nullable();
            $table->mediumText('cf_array')->nullable();
            $table->mediumText('cf_array_display')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
