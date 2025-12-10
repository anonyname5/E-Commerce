<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Log the request information for debugging
        \Illuminate\Support\Facades\Log::info('Product Store Request', [
            'all_input' => $request->all(),
            'has_files' => $request->hasFile('images'),
            'file_keys' => array_keys($request->allFiles()),
            'content_type' => $request->header('Content-Type'),
        ]);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:products,slug',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Handle is_active checkbox properly
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        // Handle is_featured checkbox properly
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

        // Create the product
        $product = Product::create($validated);
        
        // Direct check for files
        $uploadedFiles = $request->file('images');
        
        \Illuminate\Support\Facades\Log::info('Direct file check', [
            'has_uploaded_files' => !empty($uploadedFiles),
            'uploaded_files_count' => is_array($uploadedFiles) ? count($uploadedFiles) : 0
        ]);
        
        // Handle multiple image uploads - more direct approach
        if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
            \Illuminate\Support\Facades\Log::info('Processing ' . count($uploadedFiles) . ' images');
            
            foreach ($uploadedFiles as $index => $file) {
                try {
                    \Illuminate\Support\Facades\Log::info('Processing file at index ' . $index);
                    $path = $file->store('products', 'public');
                    
                    // If this is the first image, make it primary
                    $isPrimary = ($index === 0 && $product->images()->count() === 0);
                    
                    $product->images()->create([
                        'image' => $path,
                        'is_primary' => $isPrimary,
                        'display_order' => $index
                    ]);
                    
                    \Illuminate\Support\Facades\Log::info('Saved file: ' . $path);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Error saving file: ' . $e->getMessage());
                }
            }
        } else {
            \Illuminate\Support\Facades\Log::warning('No files found in the request');
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:products,slug',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Update the product
        $product->update($validated);
        
        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($request->file('images'), $product);
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // Delete all product images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }

    /**
     * Handle multiple image uploads for a product
     */
    private function handleImageUploads($images, Product $product)
    {
        \Illuminate\Support\Facades\Log::info('Starting handleImageUploads', [
            'product_id' => $product->id,
            'images_count' => count($images)
        ]);

        $displayOrder = $product->images()->count();
        
        foreach ($images as $index => $image) {
            try {
                \Illuminate\Support\Facades\Log::info('Processing image', [
                    'index' => $index,
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'mime' => $image->getMimeType()
                ]);
                
                $path = $image->store('products', 'public');
                \Illuminate\Support\Facades\Log::info('Image stored at: ' . $path);
                
                // If this is the first image, make it primary
                $isPrimary = ($displayOrder === 0);
                
                $productImage = $product->images()->create([
                    'image' => $path,
                    'is_primary' => $isPrimary,
                    'display_order' => $displayOrder++
                ]);
                
                \Illuminate\Support\Facades\Log::info('Product image created', [
                    'product_image_id' => $productImage->id,
                    'path' => $path,
                    'is_primary' => $isPrimary,
                    'display_order' => $displayOrder - 1
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error processing image', [
                    'index' => $index,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        \Illuminate\Support\Facades\Log::info('Completed handleImageUploads', [
            'product_id' => $product->id,
            'total_images' => $product->images()->count()
        ]);
    }

    /**
     * Delete a specific product image
     */
    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $productId = $image->product_id;
        
        // Delete the file
        Storage::disk('public')->delete($image->image);
        
        // If this was the primary image, set another one as primary
        if ($image->is_primary) {
            $nextImage = ProductImage::where('product_id', $productId)
                ->where('id', '!=', $id)
                ->first();
                
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }
        
        // Delete the record
        $image->delete();
        
        // Reorder the remaining images
        $remainingImages = ProductImage::where('product_id', $productId)
            ->orderBy('display_order')
            ->get();
            
        $order = 0;
        foreach ($remainingImages as $img) {
            $img->update(['display_order' => $order++]);
        }
        
        return back()->with('success', 'Image deleted successfully');
    }

    /**
     * Set an image as the primary image for a product
     */
    public function setPrimaryImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $productId = $image->product_id;
        
        // Remove primary status from all other images
        ProductImage::where('product_id', $productId)
            ->update(['is_primary' => false]);
            
        // Set this image as primary
        $image->update(['is_primary' => true]);
        
        return back()->with('success', 'Primary image updated successfully');
    }

    /**
     * Display a catalog of products for customers.
     */
    public function catalog(Request $request)
    {
        $query = Product::where('is_active', true);
        
        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Search by name if provided
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->with(['category', 'images'])->paginate(12);
        $categories = Category::all();
        
        return view('shop.catalog', compact('products', 'categories'));
    }
    
    /**
     * Display a single product for customers.
     */
    public function show(Product $product)
    {
        // Load the product with its images and category
        $product->load(['images', 'category']);
        
        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('primaryImage')
            ->take(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display products by category.
     */
    public function byCategory(Category $category)
    {
        // Get IDs of this category and all its children
        $categoryIds = $category->children->pluck('id')->push($category->id)->toArray();
        
        // Get products from this category and all its subcategories
        $products = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->with('images')
            ->paginate(12);
            
        $categories = Category::all();
        
        // Load the parent category if it exists
        $category->load('parent');
        
        return view('shop.catalog', compact('products', 'categories', 'category'));
    }
}
