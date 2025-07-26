<x-header />
       
<x-top-menu />

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $tableName }}</h1>
                <p class="text-gray-600">Browse Table Data</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('database.tables') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    ← Back to Tables
                </a>
                <a href="{{ route('database.table.show', ['table' => $tableName]) }}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    View Structure
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Pagination Info -->
    @if(isset($pagination) && $pagination['total'] > 0)
        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ $pagination['from'] }}</span> to 
                <span class="font-medium">{{ $pagination['to'] }}</span> of 
                <span class="font-medium">{{ $pagination['total'] }}</span> records
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-700">Per page:</span>
                <select onchange="changePerPage(this.value)" class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        @if(empty($data) || $data->isEmpty())
            <div class="p-6 text-center text-gray-500">
                No data found in this table.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach($columns as $column)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $column }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data as $row)
                            <tr class="hover:bg-gray-50">
                                @foreach($columns as $column)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @php
                                            $value = $row->$column ?? null;
                                        @endphp
                                        
                                        @if(is_null($value))
                                            <span class="text-gray-400 italic">NULL</span>
                                        @elseif(is_bool($value))
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $value ? 'TRUE' : 'FALSE' }}
                                            </span>
                                        @elseif(is_numeric($value))
                                            <span class="font-mono">{{ $value }}</span>
                                        @elseif(strlen($value) > 100)
                                            <div class="max-w-xs">
                                                <span class="block truncate" title="{{ $value }}">{{ $value }}</span>
                                                <button onclick="showFullText('{{ addslashes($value) }}')" 
                                                        class="text-blue-600 hover:text-blue-800 text-xs">
                                                    Show full text
                                                </button>
                                            </div>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($pagination) && $pagination['last_page'] > 1)
        <div class="mt-6 flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                @if($pagination['current_page'] > 1)
                    <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => $pagination['current_page'] - 1])) }}" 
                       class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                @endif
                
                @if($pagination['current_page'] < $pagination['last_page'])
                    <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => $pagination['current_page'] + 1])) }}" 
                       class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                @endif
            </div>
            
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Page <span class="font-medium">{{ $pagination['current_page'] }}</span> of 
                        <span class="font-medium">{{ $pagination['last_page'] }}</span>
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{-- Previous Page Link --}}
                        @if($pagination['current_page'] > 1)
                            <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => $pagination['current_page'] - 1])) }}" 
                               class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @php
                            $start = max(1, $pagination['current_page'] - 2);
                            $end = min($pagination['last_page'], $pagination['current_page'] + 2);
                        @endphp

                        @for($i = $start; $i <= $end; $i++)
                            @if($i == $pagination['current_page'])
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">
                                    {{ $i }}
                                </span>
                            @else
                                <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => $i])) }}" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if($pagination['current_page'] < $pagination['last_page'])
                            <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->query(), ['page' => $pagination['current_page'] + 1])) }}" 
                               class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                Next
                            </a>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal for showing full text -->
<div id="textModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Full Text</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3">
                <div id="modalContent" class="text-sm text-gray-700 whitespace-pre-wrap max-h-96 overflow-y-auto bg-gray-50 p-4 rounded"></div>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="closeModal()" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    }

    function showFullText(text) {
        document.getElementById('modalContent').textContent = text;
        document.getElementById('textModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('textModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('textModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endpush

<x-footer />