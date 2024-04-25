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
        Schema::create('taxonomy_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxonomy_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('tree_ids', 250)->nullable();
            $table->string('name', 150);            
            $table->text('description')->nullable();
            $table->tinyInteger('active');
            $table->smallInteger('position')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('image', 200)->nullable();
            $table->string('icon', 250)->nullable();
            $table->integer('count_items')->nullable();
            $table->integer('count_tree_items')->nullable();
            $table->integer('cf_group_id')->nullable();
            $table->timestamps();

            $table->foreign('taxonomy_id')->references('id')->on('taxonomy')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxonomy_items');
    }
};
