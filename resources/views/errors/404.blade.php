<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Growtopia Calculator</title>
    <meta name="description" content="The page you were looking for could not be found.">
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Nunito', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc] flex items-center justify-center p-6" style="background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 20px 20px;">
    <div class="text-center max-w-md mx-auto">
        <!-- Icon -->
        <div class="w-24 h-24 bg-white rounded-3xl shadow-md border border-slate-200 flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <!-- Error Code -->
        <p class="text-[80px] font-black text-slate-200 leading-none select-none">404</p>

        <!-- Heading -->
        <h1 class="text-2xl font-extrabold text-slate-800 mt-2 mb-3">Page Not Found</h1>

        <!-- Message -->
        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-8">
            Looks like this page doesn't exist or was moved. Head back to the dashboard and find what you need.
        </p>

        <!-- CTA -->
        <a href="/"
           class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white font-bold px-6 py-3 rounded-2xl transition-all duration-200 active:scale-95 shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Back to Dashboard
        </a>

        <!-- Branding -->
        <p class="text-xs text-slate-400 font-semibold mt-10 tracking-wider uppercase">Growtopia Calculator</p>
    </div>
</body>
</html>
