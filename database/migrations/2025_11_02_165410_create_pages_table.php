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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            // Foreign key to menus table
            $table->unsignedBigInteger('menu_id')->index();

            // Banner fields
            $table->string('banner_image')->nullable();
            $table->string('banner_heading');
            $table->text('banner_description')->nullable();

            // Ordering / display
            $table->integer('serial_no')->nullable();

            // SEO and URL fields
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('focus_keywords')->nullable();

            // Schema JSON (stored as text)
            $table->json('schema')->nullable();

            // Redirect fields
            $table->string('redirect_301')->nullable();
            $table->string('redirect_302')->nullable();

            $table->boolean('active')->default(false); 
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
