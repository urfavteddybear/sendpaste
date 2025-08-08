@extends('layouts.app')

@section('title', 'Password Required - SendPaste')
@section('description', 'This paste is password protected')

@section('content')
<div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-8">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Password Required</h2>
                <p class="text-gray-600">This paste is protected with a password. Please enter the password to view its content.</p>
            </div>

            <form action="{{ route('paste.password', $paste->slug) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                        autofocus
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter password"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <button 
                    type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                    View Paste
                </button>
            </form>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <a 
                        href="{{ route('paste.create') }}" 
                        class="text-sm text-blue-600 hover:text-blue-500 transition-colors"
                    >
                        Create a new paste instead
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6 text-center text-sm text-gray-500">
        <p>Paste ID: <span class="font-mono">{{ $paste->slug }}</span></p>
    </div>
</div>

<script>
// Auto-focus password field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('password').focus();
});

// Handle Enter key
document.getElementById('password').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        this.form.submit();
    }
});
</script>
@endsection
