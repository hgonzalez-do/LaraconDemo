<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpacesController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('spaces');
        $allFiles = $disk->allFiles(); // Flat array of all file paths
        $tree = $this->buildTree($allFiles);

        return view('spaces.tree', compact('tree'));
    }

    // Helper function to build nested tree
    private function buildTree(array $files)
    {
        $tree = [];
        foreach ($files as $file) {
            $parts = explode('/', $file);
            $ref = &$tree;
            while (count($parts) > 1) {
                $dir = array_shift($parts);
                $ref = &$ref['folders'][$dir];
            }
            $ref['files'][] = array_shift($parts);
        }
        return $tree;
    }
}