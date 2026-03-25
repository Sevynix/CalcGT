<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GrowtopiaHelp')</title>
    <!-- Tailwind CSS Compiler -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                        }
                    },
                    boxShadow: {
                        'glass': '0 4px 30px rgba(0, 0, 0, 0.05)',
                        'inner-light': 'inset 0 2px 4px 0 rgba(255, 255, 255, 0.4)',
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }

        .input-cool {
            @apply w-full bg-white border-2 border-slate-300 text-slate-900 text-lg font-bold rounded-xl focus:ring-4 focus:ring-brand-100 focus:border-brand-500 block p-4 outline-none transition-all duration-200 shadow-sm placeholder:font-medium placeholder:text-slate-400;
        }

        /* Remove arrows from number inputs */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc] text-slate-800 flex font-sans antialiased" x-data="{ sidebarOpen: true }">

    @include('partials.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-h-screen bg-transparent">
        
        @include('partials.header')

        <!-- Content Area -->
        <main class="flex-1 w-full flex flex-col">
            
            <!-- Where individual blade views render -->
            <div class="min-h-[calc(100vh-8rem)] w-full p-6 md:p-8">
                @yield('content')
            </div>
            
            @include('partials.footer')
        </main>
        
    </div>

    @yield('scripts')
</body>
</html>
