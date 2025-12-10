<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'slug', 'image', 'description', 'is_active', 'parent_id'];
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the parent category if it exists.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    /**
     * Check if this category is a parent category (has no parent).
     */
    public function isParent()
    {
        return is_null($this->parent_id);
    }
    
    /**
     * Check if this category has any subcategories.
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }
    
    /**
     * Get all products from this category and its subcategories.
     */
    public function allProducts()
    {
        $categoryIds = $this->children()->pluck('id')->push($this->id)->toArray();
        return Product::whereIn('category_id', $categoryIds);
    }
}
