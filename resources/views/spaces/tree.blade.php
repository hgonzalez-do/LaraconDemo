<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spaces Bucket File Tree</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <style>
        ul { margin-left: 1em; }
        .folder { font-weight: 600; }
        .file { font-family: monospace; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Spaces Bucket File Tree</h1>
        @if($tree)
            <ul>
                @include('spaces._tree', ['tree' => $tree])
            </ul>
        @else
            <p>No files found in bucket.</p>
        @endif
    </div>
</body>
</html>