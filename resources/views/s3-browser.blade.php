<x-header />

<x-top-menu />

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg">
        <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center"><img class="w-6 h-6 mr-2"
                src="https://docs.digitalocean.com/images/icons/spaces.svg" alt="Spaces"> 
                Spaces File Browser
            </h1>
            
            <!-- Breadcrumb Navigation -->
            <nav class="mt-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        @if($index === count($breadcrumbs) - 1)
                            <li class="text-gray-800 font-medium">{{ $breadcrumb['name'] }}</li>
                        @else
                            <li>
                                <a href="{{ route('s3.browser', ['path' => $breadcrumb['path']]) }}" 
                                   class="hover:text-blue-600 transition-colors duration-200">
                                    {{ $breadcrumb['name'] }}
                                </a>
                            </li>
                            @if($index < count($breadcrumbs) - 1)
                                <li class="text-gray-400">/</li>
                            @endif
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-6 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- File Listing -->
        <div class="p-6">
            @if(empty($folders) && empty($files))
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 2a1 1 0 000 2h6a1 1 0 100-2H9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No files or folders</h3>
                    <p class="mt-1 text-sm text-gray-500">This directory appears to be empty.</p>
                </div>
            @else
                <div class="grid gap-2">
                    <!-- Folders -->
                    @foreach($folders as $folder)
                        <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200 group">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <a href="{{ route('s3.browser', ['path' => $folder['path']]) }}" 
                                   class="block">
                                    <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                                        {{ $folder['name'] }}
                                    </p>
                                    <p class="text-sm text-gray-500">Folder</p>
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    @endforeach

                    <!-- Files -->
                    @foreach($files as $file)
                        <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200 group">
                            <div class="flex-shrink-0">
                                @php
                                    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                    $iconColor = match($extension) {
                                        'pdf' => 'text-red-500',
                                        'doc', 'docx' => 'text-blue-600',
                                        'xls', 'xlsx' => 'text-green-600',
                                        'ppt', 'pptx' => 'text-orange-500',
                                        'jpg', 'jpeg', 'png', 'gif', 'svg' => 'text-purple-500',
                                        'mp4', 'avi', 'mov' => 'text-pink-500',
                                        'mp3', 'wav' => 'text-yellow-500',
                                        'zip', 'rar', '7z' => 'text-gray-600',
                                        default => 'text-gray-500'
                                    };
                                @endphp
                                <svg class="h-8 w-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $file['name'] }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $file['size'] }} • {{ $file['last_modified'] }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 flex items-center space-x-2">
                                <!-- View/Open Link -->
                                <a href="{{ $file['url'] }}" 
                                   target="_blank"
                                   class="p-2 text-gray-400 hover:text-blue-600 transition-colors duration-200"
                                   title="View file">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                
                                <!-- Download Link -->
                                <a href="{{ route('s3.download', ['file' => $file['path']]) }}" 
                                   class="p-2 text-gray-400 hover:text-green-600 transition-colors duration-200"
                                   title="Download file">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush

<x-footer />