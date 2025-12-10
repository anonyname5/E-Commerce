<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First add the columns without constraints
        Schema::table('products', function (Blueprint $table) {
            // Add slug column if it doesn't exist
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->after('name')->nullable();
            }
            
            // Add is_featured column if it doesn't exist
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
        });
        
        // Update existing products with slugs based on their names
        $products = DB::table('products')->whereNull('slug')->orWhere('slug', '')->get();
        foreach ($products as $product) {
            $slug = \Illuminate\Support\Str::slug($product->name);
            $originalSlug = $slug;
            $count = 1;
            
            // Make sure the slug is unique
            while (DB::table('products')->where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            
            DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
        }
        
        // Now add the unique constraint
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'slug')) {
                // Drop the index if it exists
                $table->unique('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('products', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
            
            if (Schema::hasColumn('products', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};
