<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get main categories with their children
        $parentCategories = Category::with('children')
            ->whereNull('parent_id')
            ->paginate(10);
            
        // Get all categories for parent selection
        $allCategories = Category::all();
        
        return view('admin.categories.index', compact('parentCategories', 'allCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        
        // Default value for is_active if not provided
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        
        // Handle null parent_id (convert empty string to null)
        if (empty($validated['parent_id'])) {
            $validated['parent_id'] = null;
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }
        
        Category::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('parent', 'children');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // Get all categories for parent selection, excluding the current category and its children
        $categories = Category::where('id', '!=', $category->id)
            ->whereNotIn('id', $category->children()->pluck('id')->toArray())
            ->get();
            
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                'is_active' => 'boolean',
                'parent_id' => 'nullable|exists:categories,id',
            ]);
            
            // Log request for debugging
            \Illuminate\Support\Facades\Log::info('Category Update Request', [
                'id' => $category->id,
                'request' => $request->all(),
                'validated' => $validated
            ]);
            
            $validated['slug'] = Str::slug($validated['name']);
            
            // Default value for is_active if not provided
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            
            // Handle null parent_id (convert empty string to null)
            if (empty($validated['parent_id'])) {
                $validated['parent_id'] = null;
            }
            
            // Prevent circular references - a category cannot be its own parent or child
            if ($validated['parent_id'] == $category->id) {
                return redirect()->back()
                    ->with('error', 'A category cannot be its own parent')
                    ->withInput();
            }
            
            // Prevent making a category a child of its own child (circular reference)
            if ($validated['parent_id'] !== null) {
                $childIds = $category->children()->pluck('id')->toArray();
                if (in_array($validated['parent_id'], $childIds)) {
                    return redirect()->back()
                        ->with('error', 'Cannot set a child category as the parent (circular reference)')
                        ->withInput();
                }
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                
                $path = $request->file('image')->store('categories', 'public');
                $validated['image'] = $path;
            }
            
            $category->update($validated);
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Category Update Error', [
                'id' => $category->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error updating category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with associated products');
        }
        
        // Check if category has child categories
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with subcategories. Delete subcategories first.');
        }
        
        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }
} 