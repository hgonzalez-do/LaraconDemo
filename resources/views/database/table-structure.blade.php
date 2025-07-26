
<x-header />

<x-top-menu />

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $tableName }}</h1>
                <p class="text-gray-600">Table Structure & Schema</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('database.tables') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    ← Back to Tables
                </a>
                <a href="{{ route('database.table.data', ['table' => $tableName]) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Browse Data
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Columns Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Columns</h2>
        </div>
        
        @if(empty($columns))
            <div class="p-6 text-center text-gray-500">
                No columns found for this table.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Column Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nullable</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Default</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Length/Precision</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($columns as $column)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $column->column_name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $column->data_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($column->is_nullable === 'YES')
                                        <span class="text-green-600">✓ Yes</span>
                                    @else
                                        <span class="text-red-600">✗ No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $column->column_default ?? 'NULL' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($column->character_maximum_length)
                                        {{ $column->character_maximum_length }}
                                    @elseif($column->numeric_precision)
                                        {{ $column->numeric_precision }}
                                        @if($column->numeric_scale), {{ $column->numeric_scale }}@endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Indexes Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Indexes</h2>
        </div>
        
        @if(empty($indexes))
            <div class="p-6 text-center text-gray-500">
                No indexes found for this table.
            </div>
        @else
            <div class="p-6">
                @foreach($indexes as $index)
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">{{ $index->indexname }}</h3>
                        <code class="text-sm text-gray-600 bg-gray-100 p-2 rounded block">{{ $index->indexdef }}</code>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Foreign Keys Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Foreign Keys</h2>
        </div>
        
        @if(empty($foreignKeys))
            <div class="p-6 text-center text-gray-500">
                No foreign keys found for this table.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Column</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">References</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foreign Table</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foreign Column</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($foreignKeys as $fk)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $fk->column_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    →
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $fk->foreign_table_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $fk->foreign_column_name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<x-footer />