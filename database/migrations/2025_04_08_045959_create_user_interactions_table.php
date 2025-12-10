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
        Schema::create('user_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // For guest users
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('interaction_type', ['view', 'add_to_cart', 'purchase', 'wishlist', 'search', 'recommendation_click']);
            $table->integer('duration')->nullable(); // Time spent viewing in seconds
            $table->string('search_query')->nullable(); // If interaction is search
            $table->json('metadata')->nullable(); // Additional interaction data like recommendation source
            $table->timestamps();
            
            $table->index(['user_id', 'interaction_type']);
            $table->index(['session_id', 'interaction_type']);
            $table->index(['product_id', 'interaction_type']);
            $table->index('created_at'); // For time-based queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_interactions');
    }
};
