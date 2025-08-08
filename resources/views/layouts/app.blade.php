<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SendPaste - Secure Text Sharing')</title>

    <meta name="description" content="@yield('description', 'Share text securely with encryption and automatic expiration. Clean, minimalist pastebin service.')">
    <meta name="robots" content="noindex, nofollow">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 font-sans antialiased">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('paste.create') }}" class="text-xl font-semibold text-gray-900 hover:text-gray-700 transition-colors">
                            SendPaste
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('paste.create') }}" class="text-gray-600 hover:text-gray-900 transition-colors text-sm font-medium">
                            New Paste
                        </a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors text-sm font-medium" onclick="showApiInfo()">
                            API
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    There {{ $errors->count() === 1 ? 'was' : 'were' }} {{ $errors->count() }} error{{ $errors->count() === 1 ? '' : 's' }} with your submission
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-500">
                    <p>SendPaste - Secure, encrypted text sharing. Content is encrypted in the database.</p>
                    <p class="mt-1">No logs are kept. Pastes expire automatically.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- API Info Modal -->
    <div id="apiModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">API Documentation</h3>
                    <button onclick="hideApiInfo()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="text-sm text-gray-700 space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Create a paste</h4>
                        <div class="bg-gray-100 p-3 rounded text-xs font-mono">
                            <div>POST {{ url('/api/paste') }}</div>
                            <div class="mt-2">Content-Type: application/json</div>
                            <div class="mt-2">{</div>
                            <div>&nbsp;&nbsp;"content": "Your text here",</div>
                            <div>&nbsp;&nbsp;"title": "Optional title",</div>
                            <div>&nbsp;&nbsp;"language": "text",</div>
                            <div>&nbsp;&nbsp;"expiration": "1week"</div>
                            <div>}</div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Expiration options</h4>
                        <p class="text-sm">1week, 1month, 3months, 6months, 1year, never</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Raw content</h4>
                        <div class="bg-gray-100 p-3 rounded text-xs font-mono">
                            GET {{ url('/SLUG/raw') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showApiInfo() {
            document.getElementById('apiModal').classList.remove('hidden');
        }
        
        function hideApiInfo() {
            document.getElementById('apiModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('apiModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideApiInfo();
            }
        });
    </script>
</body>
</html>
