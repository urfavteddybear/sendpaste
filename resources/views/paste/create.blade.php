@extends('layouts.app')

@section('title', 'Create New Paste - SendPaste')
@section('description', 'Create a new encrypted paste with automatic expiration')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Create a New Paste</h1>
        <p class="text-gray-600">Share text securely with encryption and automatic expiration</p>
    </div>

    <form action="{{ route('paste.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title (optional)</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            value="{{ old('title') }}"
                            placeholder="Untitled paste"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                        >
                    </div>
                    
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                        <select 
                            name="language" 
                            id="language"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                        >
                            <option value="text" {{ old('language') === 'text' ? 'selected' : '' }}>Plain Text</option>
                            <option value="javascript" {{ old('language') === 'javascript' ? 'selected' : '' }}>JavaScript</option>
                            <option value="python" {{ old('language') === 'python' ? 'selected' : '' }}>Python</option>
                            <option value="php" {{ old('language') === 'php' ? 'selected' : '' }}>PHP</option>
                            <option value="html" {{ old('language') === 'html' ? 'selected' : '' }}>HTML</option>
                            <option value="css" {{ old('language') === 'css' ? 'selected' : '' }}>CSS</option>
                            <option value="json" {{ old('language') === 'json' ? 'selected' : '' }}>JSON</option>
                            <option value="xml" {{ old('language') === 'xml' ? 'selected' : '' }}>XML</option>
                            <option value="sql" {{ old('language') === 'sql' ? 'selected' : '' }}>SQL</option>
                            <option value="bash" {{ old('language') === 'bash' ? 'selected' : '' }}>Bash</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="expiration" class="block text-sm font-medium text-gray-700 mb-1">Expiration</label>
                        <select 
                            name="expiration" 
                            id="expiration"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                        >
                            <option value="1week" {{ old('expiration', '1week') === '1week' ? 'selected' : '' }}>1 Week</option>
                            <option value="1month" {{ old('expiration') === '1month' ? 'selected' : '' }}>1 Month</option>
                            <option value="3months" {{ old('expiration') === '3months' ? 'selected' : '' }}>3 Months</option>
                            <option value="6months" {{ old('expiration') === '6months' ? 'selected' : '' }}>6 Months</option>
                            <option value="1year" {{ old('expiration') === '1year' ? 'selected' : '' }}>1 Year</option>
                            <option value="never" {{ old('expiration') === 'never' ? 'selected' : '' }}>Never</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                <textarea 
                    name="content" 
                    id="content" 
                    rows="20"
                    placeholder="Paste your content here..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm font-mono resize-y"
                    required
                >{{ old('content') }}</textarea>
                <p class="mt-2 text-xs text-gray-500">Maximum size: 500KB. Content will be encrypted in the database.</p>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex-1">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Protection (optional)</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="Leave blank for no password"
                            class="w-full sm:max-w-xs px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                        >
                    </div>
                    
                    <div class="flex gap-3">
                        <button 
                            type="button" 
                            onclick="clearForm()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            Clear
                        </button>
                        <button 
                            type="submit"
                            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            Create Paste
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-blue-900 mb-3">🔒 Security Features</h3>
        <ul class="text-sm text-blue-800 space-y-2">
            <li class="flex items-start">
                <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                All content is encrypted using Laravel's built-in encryption
            </li>
            <li class="flex items-start">
                <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Database administrators cannot read your content
            </li>
            <li class="flex items-start">
                <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Automatic expiration ensures content doesn't persist forever
            </li>
            <li class="flex items-start">
                <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Optional password protection for additional security
            </li>
        </ul>
    </div>
</div>

<script>
function clearForm() {
    if (confirm('Are you sure you want to clear the form?')) {
        document.getElementById('content').value = '';
        document.getElementById('title').value = '';
        document.getElementById('password').value = '';
        document.getElementById('language').value = 'text';
        document.getElementById('expiration').value = '1week';
    }
}

// Auto-resize textarea
document.getElementById('content').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});

// Character counter
document.getElementById('content').addEventListener('input', function() {
    const length = new Blob([this.value]).size;
    const maxSize = 500 * 1024; // 500KB
    
    if (length > maxSize) {
        this.value = this.value.substring(0, this.value.length - 1);
    }
});
</script>
@endsection
