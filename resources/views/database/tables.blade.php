<x-header />

<x-top-menu />


<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h1 class="text-2xl font-bold text-gray-800">PostgreSQL Database Tables</h1>
            <p class="text-gray-600 mt-1">Database: {{ config('database.connections.pgsql.database') }}</p>
        </div>

        @if($tables->isEmpty())
            <div class="p-6 text-center">
                <p class="text-gray-500">No tables found in the database.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Table Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Schema
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tables as $index => $table)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-6 w-6">
                                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $table->tablename ?? $table->table_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $table->schemaname ?? $table->table_schema ?? 'public' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('database.table.show', ['table' => $table->tablename ?? $table->table_name]) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        View Structure
                                    </a>
                                    <a href="{{ route('database.table.data', ['table' => $table->tablename ?? $table->table_name]) }}" 
                                       class="text-green-600 hover:text-green-900">
                                        Browse Data
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 px-6 py-3 border-t">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $tables->count() }}</span> table(s)
                    </div>
                    <div class="text-sm text-gray-500">
                        Last updated: {{ now()->format('M d, Y H:i:s') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Optional: Add some interactivity
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                if (!e.target.closest('a')) {
                    const firstLink = this.querySelector('a');
                    if (firstLink) {
                        window.location.href = firstLink.href;
                    }
                }
            });
        });
    });
</script>
@endpush


<x-footer />