@isset($tree['folders'])
    @foreach($tree['folders'] as $folderName => $subtree)
        <li class="folder text-blue-600">
            <span>📁 {{ $folderName }}</span>
            <ul>
                @include('spaces._tree', ['tree' => $subtree])
            </ul>
        </li>
    @endforeach
@endisset
@isset($tree['files'])
    @foreach($tree['files'] as $file)
        <li class="file text-gray-800">📄 {{ $file }}</li>
    @endforeach
@endisset
