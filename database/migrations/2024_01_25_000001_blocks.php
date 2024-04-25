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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
            $table->mediumText('content')->nullable();
            $table->text('header')->nullable();
            $table->string('label', 200)->nullable();
            $table->text('extra')->nullable();
            $table->string('module', 100)->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->integer('position')->default(0);
            $table->tinyInteger('hide')->default(0);
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->timestamps();

            $table->foreign('created_by_user_id')->references('id')->on('users')->nullonDelete();
            $table->foreign('updated_by_user_id')->references('id')->on('users')->nullonDelete();
            $table->foreign('post_id')->references('id')->on('posts')->cascadeonDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
