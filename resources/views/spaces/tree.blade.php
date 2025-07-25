<x-header />
       
<x-top-menu />

    <div class="max-w-2xl mx-auto bg-white rounded shadow p-10">
        <h1 class="text-2xl font-bold mb-4">Spaces Bucket File Tree</h1>
        @if($tree)
            <ul>
                @include('spaces._tree', ['tree' => $tree])
            </ul>
        @else
            <p>No files found in bucket.</p>
        @endif
    </div>

<x-footer />