<x-header />

<x-top-menu />

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg">
        <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <img class="card-icon pr-2"
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

        <!-- Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mx-6 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-6 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        @foreach($errors->all() as $error)
                            <p class="text-sm text-red-700">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- File Listing -->
        <div class="p-6">
            <!-- Upload Section -->
            <div class="mb-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 hover:border-gray-400 transition-colors duration-200" id="upload-area">
                <div class="p-6">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="mt-4">
                            <label for="file-upload" class="cursor-pointer">
                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                    Drop files here or click to upload
                                </span>
                                <span class="mt-1 block text-sm text-gray-500">
                                    Maximum file size: 100MB per file
                                </span>
                                <input id="file-upload" name="files[]" type="file" class="sr-only" multiple>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mb-6 flex flex-wrap gap-4">
                <button type="button" id="upload-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center" disabled>
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Upload Files
                </button>
                
                <button type="button" id="create-folder-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Folder
                </button>
            </div>

            <!-- Upload Progress -->
            <div id="upload-progress" class="mb-6 hidden">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-blue-800">Uploading files...</span>
                        <span class="text-sm text-blue-600" id="progress-text">0%</span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

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

                                <!-- Delete Link -->
                                <button type="button" 
                                        onclick="deleteFile('{{ $file['path'] }}', '{{ $file['name'] }}')"
                                        class="p-2 text-gray-400 hover:text-red-600 transition-colors duration-200"
                                        title="Delete file">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div id="folder-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Create New Folder</h3>
                <button type="button" id="close-folder-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('s3.create-folder') }}" method="POST">
                @csrf
                <input type="hidden" name="path" value="{{ $path }}">
                <div class="mb-4">
                    <label for="folder_name" class="block text-sm font-medium text-gray-700 mb-2">Folder Name</label>
                    <input type="text" 
                           id="folder_name" 
                           name="folder_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter folder name" 
                           required>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            id="cancel-folder" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200">
                        Create Folder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Delete File</h3>
                <button type="button" id="close-delete-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete <strong id="delete-file-name"></strong>? This action cannot be undone.</p>
            <form id="delete-form" action="{{ route('s3.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="file" id="delete-file-path">
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            id="cancel-delete" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors duration-200">
                        Delete File
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Upload Form -->
<form id="upload-form" action="{{ route('s3.upload') }}" method="POST" enctype="multipart/form-data" class="hidden">
    @csrf
    <input type="hidden" name="path" value="{{ $path }}">
</form>

@push('scripts')
<script>
    let selectedFiles = [];
    
    // File upload functionality
    const fileUpload = document.getElementById('file-upload');
    const uploadArea = document.getElementById('upload-area');
    const uploadBtn = document.getElementById('upload-btn');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-blue-400', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = Array.from(e.dataTransfer.files);
        handleFileSelection(files);
    });
    
    uploadArea.addEventListener('click', () => {
        fileUpload.click();
    });
    
    fileUpload.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        handleFileSelection(files);
    });
    
    function handleFileSelection(files) {
        selectedFiles = files;
        updateUploadButton();
    }
    
    function updateUploadButton() {
        if (selectedFiles.length > 0) {
            uploadBtn.disabled = false;
            uploadBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            uploadBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Upload ${selectedFiles.length} file${selectedFiles.length > 1 ? 's' : ''}
            `;
        } else {
            uploadBtn.disabled = true;
            uploadBtn.classList.add('opacity-50', 'cursor-not-allowed');
            uploadBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Upload Files
            `;
        }
    }
    
    // Upload files
    uploadBtn.addEventListener('click', () => {
        if (selectedFiles.length === 0) return;
        
        const formData = new FormData();
        formData.append('path', '{{ $path }}');
        formData.append('_token', '{{ csrf_token() }}');
        
        selectedFiles.forEach(file => {
            formData.append('files[]', file);
        });
        
        uploadProgress.classList.remove('hidden');
        uploadBtn.disabled = true;
        
        fetch('{{ route("s3.upload") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(() => {
            window.location.reload();
        })
        .catch(error => {
            console.error('Upload error:', error);
            uploadProgress.classList.add('hidden');
            uploadBtn.disabled = false;
            alert('Upload failed. Please try again.');
        });
    });
    
    // Folder modal functionality
    const createFolderBtn = document.getElementById('create-folder-btn');
    const folderModal = document.getElementById('folder-modal');
    const closeFolderModal = document.getElementById('close-folder-modal');
    const cancelFolder = document.getElementById('cancel-folder');
    
    createFolderBtn.addEventListener('click', () => {
        folderModal.classList.remove('hidden');
        document.getElementById('folder_name').focus();
    });
    
    [closeFolderModal, cancelFolder].forEach(btn => {
        btn.addEventListener('click', () => {
            folderModal.classList.add('hidden');
            document.getElementById('folder_name').value = '';
        });
    });
    
    // Delete modal functionality
    const deleteModal = document.getElementById('delete-modal');
    const closeDeleteModal = document.getElementById('close-delete-modal');
    const cancelDelete = document.getElementById('cancel-delete');
    
    function deleteFile(filePath, fileName) {
        document.getElementById('delete-file-name').textContent = fileName;
        document.getElementById('delete-file-path').value = filePath;
        deleteModal.classList.remove('hidden');
    }
    
    [closeDeleteModal, cancelDelete].forEach(btn => {
        btn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });
    });
    
    // Close modals when clicking outside
    [folderModal, deleteModal].forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
    
    // Initialize upload button state
    updateUploadButton();
</script>

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