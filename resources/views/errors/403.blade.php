<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Growtopia Calculator</title>
    <meta name="description" content="You do not have permission to access this page.">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <!-- Error Code -->
        <p class="text-[80px] font-black text-slate-200 leading-none select-none">403</p>

        <!-- Heading -->
        <h1 class="text-2xl font-extrabold text-slate-800 mt-2 mb-3">Akses Ditolak</h1>

        <!-- Message -->
        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-8">
            {{ $exception->getMessage() ?: "Looks like you don't have permission to access this page. Head back to the dashboard." }}
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
