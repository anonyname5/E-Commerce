<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    // No middleware in constructor to test if that's causing the issue
    
    public function index()
    {
        return response()->json([
            'message' => 'Admin test controller works!',
            'user' => auth()->user()->toArray()
        ]);
    }
} 