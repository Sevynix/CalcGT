<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error — Growtopia Calculator</title>
    <meta name="description" content="Something went wrong on our end. Please try again shortly.">
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
        <div class="w-24 h-24 bg-red-50 rounded-3xl shadow-md border border-red-100 flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <!-- Error Code -->
        <p class="text-[80px] font-black text-slate-200 leading-none select-none">500</p>

        <!-- Heading -->
        <h1 class="text-2xl font-extrabold text-slate-800 mt-2 mb-3">Something Went Wrong</h1>

        <!-- Message -->
        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-8">
            An unexpected error occurred on our end. Please try again in a moment. If the problem persists, the issue will be looked into.
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
