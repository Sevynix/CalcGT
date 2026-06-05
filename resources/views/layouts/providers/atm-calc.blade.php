@extends('layouts.app')
@section('title', "ATM Machine Provider Calculator")
@section('meta_description', "Calculate the return on investment, daily gems, and daily profit for ATM Machines in Growtopia.")

@section('content')
<div x-data="calculator()" @keydown.window.enter.prevent="
    let inputs = Array.from(document.querySelectorAll('input'));
    let idx = inputs.indexOf(document.activeElement);
    if(idx > -1 && idx < inputs.length - 1) {
        inputs[idx + 1].focus();
        if(inputs[idx + 1].select) inputs[idx + 1].select();
    }
">
    <div class="max-w-5xl mx-auto space-y-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <a href="/"
                    class="inline-flex items-center text-slate-500 hover:text-brand-600 transition font-medium mb-3 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Back to Dashboard
                </a>
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
                    <img src="{{ asset('images/city/atm.png') }}" alt="ATM Machine"
                        class="w-10 h-10 object-contain drop-shadow-sm">
                    ATM Machine
                    <span
                        class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Calculator</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Calculate your estimated daily gems, daily profit, and how many days it will take to break even on your ATM farm.</p>

                <div
                    class="mt-3 bg-amber-50 border border-amber-200/60 rounded-xl p-3 flex items-start gap-2.5 text-amber-700 max-w-lg shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <p class="text-[13px] leading-relaxed font-medium">
                        <strong class="font-bold">Info:</strong> ATM Machines drop an average of 11 gems each when punched, and take approximately 22 hours to reset. This calculator assumes you harvest them once per day (1 cycle = 1 day).
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex gap-3">
                <button @click="$store.modal.show('This will clear all your inputs and saved data.', () => resetCalculator())"
                    class="bg-red-500 hover:bg-red-600 active:bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-md text-sm flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Reset All
                </button>
            </div>
        </div>

        <!-- Top Dashboard Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Input Card -->
            <div class="glass-card rounded-[32px] p-8 lg:col-span-1 shadow-sm border border-slate-100 flex flex-col justify-center">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                        <img src="{{ asset('images/items/gems.png') }}" alt="Inputs" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Farm Details</h2>
                </div>

                <div class="space-y-6">
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Number of ATMs</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="0" x-model.number="numATMs"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="0">
                            <span class="text-slate-500 font-bold text-[16px] ml-2">ATMs</span>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Cost Per ATM</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="0" step="0.1" x-model.number="costPerATM"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="0">
                            <span class="text-slate-500 font-bold text-[16px] ml-2">WL</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col pt-2 border-t border-slate-100">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Current Gems Rate</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="1" x-model.number="gemsRate"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="0">
                            <span class="text-slate-500 font-bold text-[14px] ml-2 whitespace-nowrap">Gems / WL</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div
                class="bg-white rounded-[32px] p-8 lg:col-span-2 shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">

                <!-- Background Decoration -->
                <div
                    class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-emerald-400/10 to-teal-400/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                        <img src="{{ asset('images/lock/worldlock.png') }}" alt="WL" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                        <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Profit Overview</h2>
                    </div>

                    <!-- 2x2 Symmetrical Grid -->
                    <div class="flex-grow grid grid-cols-2 gap-4">

                        <!-- Total Cost -->
                        <div
                            class="bg-slate-50 border border-slate-100 rounded-[20px] p-5 flex flex-col justify-center">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Initial Cost</p>
                            <p class="text-[28px] font-extrabold text-slate-800" x-text="formatTotalCost()"></p>
                        </div>

                        <!-- Gems per Day -->
                        <div
                            class="bg-slate-50 border border-slate-100 rounded-[20px] p-5 flex flex-col justify-center relative">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Daily Gems Output</p>
                            <p class="text-[28px] font-extrabold text-emerald-600 leading-none" x-text="formatGemsPerDay()"></p>
                            <p class="text-[10px] text-slate-400 font-medium mt-2">* Avg 11 gems/ATM. Drops may be higher or lower.</p>
                        </div>

                        <!-- Daily WL Profit -->
                        <div class="bg-white border border-slate-200 rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Daily WL Profit</p>
                            <p class="text-[28px] font-extrabold text-slate-800" x-text="formatWlsPerDay()"></p>
                        </div>

                        <!-- Days to ROI -->
                        <div class="rounded-[20px] p-5 text-white shadow-md flex flex-col justify-center transform transition hover:-translate-y-1 bg-slate-800">
                            <p class="text-[12px] font-bold text-white/70 uppercase tracking-widest mb-1.5">Break-Even Day</p>
                            <p class="text-[28px] font-extrabold leading-none text-emerald-400" x-text="formatDaysToRoi()"></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function asset(path) {
        return path.startsWith('/') ? path : '/' + path;
    }
</script>
<script src="{{ asset('js/atm-calc.js') }}"></script>
@endsection
