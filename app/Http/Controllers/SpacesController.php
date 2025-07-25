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
                        'last_modified' => 'Unknown',
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