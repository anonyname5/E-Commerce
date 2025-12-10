<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestUploadController extends Controller
{
    public function showForm()
    {
        return view('test-upload-form');
    }
    
    public function handleUpload(Request $request)
    {
        // Log information about the request
        Log::info('Test Upload Request', [
            'has_file' => $request->hasFile('images'),
            'all_files' => $request->allFiles(),
            'all_input' => $request->all()
        ]);
        
        if (!$request->hasFile('images')) {
            return 'No files uploaded';
        }
        
        $output = [];
        foreach ($request->file('images') as $index => $file) {
            $output[] = [
                'index' => $index,
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'valid' => $file->isValid(),
                'path' => $file->store('test-uploads', 'public')
            ];
        }
        
        // Return details about the files
        return view('test-upload-result', ['files' => $output]);
    }
}
