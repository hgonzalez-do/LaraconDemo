<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpacesController extends Controller
{
    public function index(Request $request)
    {
        $path = $request->get('path', '');
        $path = trim($path, '/');
        
        try {
            // Get all objects with the current path as prefix
            $objects = Storage::disk('spaces')->listContents($path, false);
            
            $folders = [];
            $files = [];
            
            foreach ($objects as $object) {
                if ($object['type'] === 'dir') {
                    $folders[] = [
                        'name' => basename($object['path']),
                        'path' => $object['path'],
                        'full_path' => $object['path']
                    ];
                } else {
                    $files[] = [
                        'name' => basename($object['path']),
                        'path' => $object['path'],
                        'size' => $this->formatBytes($object['size'] ?? 0),
                        'last_modified' => isset($object['lastModified']) 
                            ? $object['lastModified'] 
                            : 'Unknown',
                        'url' => Storage::disk('spaces')->url($object['path'])
                    ];
                }
            }
            
            // Sort folders and files alphabetically
            usort($folders, fn($a, $b) => strcasecmp($a['name'], $b['name']));
            usort($files, fn($a, $b) => strcasecmp($a['name'], $b['name']));
            
            // Build breadcrumb navigation
            $breadcrumbs = $this->buildBreadcrumbs($path);
            
            return view('s3-browser', compact('folders', 'files', 'path', 'breadcrumbs'));
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to access S3 bucket: ' . $e->getMessage()]);
        }
    }
    
    public function download(Request $request)
    {
        $filePath = $request->get('file');
        
        if (!$filePath || !Storage::disk('spaces')->exists($filePath)) {
            return back()->withErrors(['error' => 'File not found']);
        }
        
        try {
            $fileName = basename($filePath);
            return Storage::disk('spaces')->download($filePath, $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to download file: ' . $e->getMessage()]);
        }
    }
    
    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:100000', // 100MB max per file
            'path' => 'string|nullable'
        ]);
        
        $path = trim($request->get('path', ''), '/');
        $uploadedFiles = [];
        $errors = [];
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                try {
                    $fileName = $file->getClientOriginalName();
                    $filePath = $path ? $path . '/' . $fileName : $fileName;
                    
                    // Check if file already exists
                    if (Storage::disk('spaces')->exists($filePath)) {
                        $errors[] = "File '{$fileName}' already exists";
                        continue;
                    }
                    
                    // Upload file to S3
                    $uploaded = Storage::disk('spaces')->putFileAs(
                        $path,
                        $file,
                        $fileName,
                        'public'
                    );
                    
                    if ($uploaded) {
                        $uploadedFiles[] = $fileName;
                    } else {
                        $errors[] = "Failed to upload '{$fileName}'";
                    }
                    
                } catch (\Exception $e) {
                    $errors[] = "Error uploading '{$file->getClientOriginalName()}': " . $e->getMessage();
                }
            }
        }
        
        $message = '';
        if (!empty($uploadedFiles)) {
            $count = count($uploadedFiles);
            $message = $count === 1 
                ? "Successfully uploaded: " . $uploadedFiles[0]
                : "Successfully uploaded {$count} files";
        }
        
        if (!empty($errors)) {
            return back()->withErrors($errors)->with('success', $message);
        }
        
        return back()->with('success', $message ?: 'No files were uploaded');
    }
    
    public function createFolder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\-_\s]+$/',
            'path' => 'string|nullable'
        ]);
        
        $path = trim($request->get('path', ''), '/');
        $folderName = trim($request->get('folder_name'));
        $folderPath = $path ? $path . '/' . $folderName : $folderName;
        
        try {
            // Create an empty .gitkeep file to create the folder
            $keepFilePath = $folderPath . '/.gitkeep';
            
            if (Storage::disk('spaces')->exists($keepFilePath)) {
                return back()->withErrors(['error' => 'Folder already exists']);
            }
            
            Storage::disk('spaces')->put($keepFilePath, '');
            
            return back()->with('success', "Folder '{$folderName}' created successfully");
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to create folder: ' . $e->getMessage()]);
        }
    }
    
    public function delete(Request $request)
    {
        $filePath = $request->get('file');
        
        if (!$filePath) {
            return back()->withErrors(['error' => 'No file specified']);
        }
        
        try {
            if (!Storage::disk('spaces')->exists($filePath)) {
                return back()->withErrors(['error' => 'File not found']);
            }
            
            Storage::disk('spaces')->delete($filePath);
            
            $fileName = basename($filePath);
            return back()->with('success', "File '{$fileName}' deleted successfully");
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to delete file: ' . $e->getMessage()]);
        }
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    private function buildBreadcrumbs($path)
    {
        $breadcrumbs = [['name' => 'Root', 'path' => '']];
        
        if (empty($path)) {
            return $breadcrumbs;
        }
        
        $pathParts = explode('/', $path);
        $currentPath = '';
        
        foreach ($pathParts as $part) {
            $currentPath .= ($currentPath ? '/' : '') . $part;
            $breadcrumbs[] = [
                'name' => $part,
                'path' => $currentPath
            ];
        }
        
        return $breadcrumbs;
    }
}