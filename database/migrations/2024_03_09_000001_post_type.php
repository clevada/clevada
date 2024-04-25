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
        Schema::create('post_type', function (Blueprint $table) {
            $table->id();
            $table->string('type', 25);
            $table->string('label', 25);
            $table->text('description')->nullable();
            $table->boolean('show_in_admin_menu')->default(false);
            $table->boolean('show_in_site_menu')->default(false);
            $table->boolean('show_in_search')->default(false);
            $table->smallInteger('admin_menu_position')->nullable();
            $table->string('admin_menu_icon', 200)->nullable();
            $table->string('slug', 200)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_type');
    }
};
