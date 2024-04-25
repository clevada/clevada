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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();            
            $table->string('file', 200);  
            $table->string('original_name', 220)->nullable();  
            $table->string('module', 50)->nullable();  
            $table->bigInteger('item_id')->nullable();  
            $table->bigInteger('extra_item_id')->nullable();  
            $table->unsignedBigInteger('created_by_user_id')->nullable();                      
            $table->char('mime_type', 30)->nullable();
            $table->char('extension', 25)->nullable();
            $table->decimal('size_mb', 10, 4)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->text('data')->nullable();                    
            $table->timestamps();

            $table->foreign('created_by_user_id')->references('id')->on('users')->nullonDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
