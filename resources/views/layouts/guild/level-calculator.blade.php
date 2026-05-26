@extends('layouts.app')
@section('title', "Guild Level Calculator")
@section('meta_description', 'Estimate guild XP requirements per level and plan contribution goals for your Growtopia guild.')

@section('content')
<div x-data="guildLevelCalculator()" @keydown.window.enter.prevent="
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
                <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight text-slate-900 flex items-center flex-wrap gap-3">
                    <img src="{{ asset('images/lock/guild_lock.png') }}" alt="Guild Level" class="w-10 h-10 object-contain drop-shadow-sm">
                    Guild Calculator
                    <span
                        class="bg-gradient-to-r from-teal-400 to-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Guild Tools</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Calculate EXP and Gem requirements needed for your Guild's progression.</p>

            </div>

            <!-- Quick Actions -->
            <div class="flex gap-3">
                <button @click="$store.modal.show('This will clear all your inputs and saved data.', () => { currentLevel = 1; targetLevel = 15; currentExp = 0; })"
                    class="bg-red-500 hover:bg-red-600 active:bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-md text-sm flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
            <div class="glass-card rounded-[32px] p-8 shadow-sm border border-slate-100 lg:col-span-1">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                        <img src="{{ asset('images/items/downarrow.png') }}" alt="Down Arrow" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Level Settings</h2>
                </div>

                <div class="space-y-6">
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Current Guild Level</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="1" max="14" x-model.number="currentLevel"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="1">
                            <span class="text-slate-500 font-bold text-[15px] ml-2 shrink-0">/ 14</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Target Guild Level</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="2" max="15" x-model.number="targetLevel"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="15">
                            <span class="text-slate-500 font-bold text-[15px] ml-2 shrink-0">/ 15</span>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2 truncate" title="Current EXP">Current EXP Progress</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="0" x-model.number="currentExp"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="0">
                            <span class="text-slate-500 font-bold text-[13px] ml-2 shrink-0">MAX <span x-text="formatNumber(getExpNeededForNextLevel())"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div
                class="bg-white rounded-[32px] p-8 lg:col-span-2 shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">

                <!-- Background Decoration -->
                <div
                    class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-teal-400/10 to-blue-400/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                        <img src="{{ asset('images/items/exclamation.png') }}" alt="Exclamation" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                        <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Progression Overview</h2>
                    </div>

                    <!-- 4-Item Stats Layout -->
                    <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Total EXP Needed -->
                        <div
                            class="bg-brand-50 border border-brand-100 rounded-[20px] p-5 flex flex-col justify-center items-center text-center shadow-sm relative overflow-hidden">
                            <p class="text-[13px] font-bold text-brand-500 uppercase tracking-widest mb-1.5 relative z-10">Total EXP Needed
                            </p>
                            <p class="text-[28px] lg:text-[32px] font-extrabold text-brand-700 leading-none relative z-10" x-text="formatNumber(calculateTotalExpNeeded())"></p>
                            <p class="text-[12px] text-brand-600/70 mt-2 font-medium relative z-10">To reach Level <span x-text="targetLevel"></span></p>
                        </div>
                        
                        <!-- Total Gems Needed -->
                        <div
                            class="bg-violet-50 border border-violet-100 rounded-[20px] p-5 flex flex-col justify-center items-center text-center shadow-sm relative overflow-hidden">
                            <div class="absolute opacity-10 blur-sm right-0 top-0">
                                <img src="{{ asset('images/lock/guild_lock.png') }}" class="w-24 h-24">
                            </div>
                            <p class="text-[13px] font-bold text-violet-500 uppercase tracking-widest mb-1.5 relative z-10">Total Gems Needed
                            </p>
                            <div class="flex items-center gap-2 relative z-10">
                                <p class="text-[28px] lg:text-[32px] font-extrabold text-violet-700 leading-none" x-text="formatNumber(calculateTotalGemsNeeded())"></p>
                                <img src="{{ asset('images/items/gems.png') }}" class="w-7 h-7 lg:w-8 lg:h-8 object-contain drop-shadow-sm" alt="Gems">
                            </div>
                            <p class="text-[12px] text-violet-600/70 mt-2 font-medium relative z-10">To reach Level <span x-text="targetLevel"></span></p>
                        </div>

                        <!-- To Next Level EXP -->
                        <div
                            class="bg-white border border-slate-200 rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-left">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">EXP to Next Level
                            </p>
                            <p class="text-[24px] font-extrabold leading-none text-slate-800" x-text="formatNumber(calculateExpToNextLevel())"></p>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">To reach Level <span x-text="Number(currentLevel) + 1"></span></p>
                        </div>
                        
                        <!-- To Next Level Gems -->
                        <div
                            class="bg-white border border-slate-200 rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-left">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Gems to Next Level
                            </p>
                            <div class="flex items-center gap-2">
                                <p class="text-[24px] font-extrabold leading-none text-slate-800" x-text="formatNumber(calculateGemsToNextLevel())"></p>
                                <img src="{{ asset('images/items/gems.png') }}" class="w-7 h-7 lg:w-8 lg:h-8 object-contain drop-shadow-sm" alt="Gems">
                            </div>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">Required for Level <span x-text="Number(currentLevel) + 1"></span> upgrade</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- The Table Area -->
        <div x-data="{ openTable: true }" class="glass-card rounded-[32px] overflow-hidden shadow-inner-light shadow-sm border border-slate-100">
            <!-- Accordion Header -->
            <button @click="openTable = !openTable" class="w-full flex items-center justify-between p-6 lg:px-8 bg-white/40 hover:bg-white/60 transition-colors text-left focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                        <img src="{{ asset('images/items/scroll.png') }}" alt="Scroll Bulletin" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-800 tracking-tight">Requirement Breakdown</h2>
                        <p class="text-[13px] text-slate-500 font-medium">EXP and Gems requirements mapped out perfectly.</p>
                    </div>
                </div>
                <div class="p-2 rounded-full bg-slate-100 text-slate-500 transition-transform duration-300" :class="openTable ? 'rotate-180' : ''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </button>

            <!-- Expanded Content -->
            <div x-show="openTable"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="border-t border-slate-100 bg-white/30"
                 style="display: none;">
                
                <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-semibold tracking-wide text-xs">
                            <th class="px-6 py-4 uppercase">Level</th>
                            <th class="px-6 py-4 uppercase">EXP Required</th>
                            <th class="px-6 py-4 uppercase">Gems Required</th>
                            <th class="px-6 py-4 uppercase text-right">Cumulative EXP</th>
                            <th class="px-6 py-4 uppercase text-right">Cumulative Gems</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(req, index) in getVisibleBreakdown()" :key="index + currentLevel + 1">
                            <tr class="hover:bg-slate-50/50 transition-colors group bg-white">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-auto px-3 h-8 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-xs border border-slate-200">
                                            <span x-text="req.levelFrom + ' → ' + req.levelTo"></span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 bg-brand-50 text-brand-700 px-3 py-1 rounded-lg font-bold text-[13px]">
                                        +<span x-text="formatNumber(req.expNeeded)"></span> EXP
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 bg-violet-50 text-violet-700 px-3 py-1 rounded-lg font-bold text-[13px]">
                                        +<span x-text="formatNumber(req.gemsNeeded)"></span> Gems
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-[14.5px] text-slate-800 block pt-1"
                                        x-text="formatNumber(req.totalEXP)"></span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-[14.5px] text-slate-800 block pt-1"
                                        x-text="formatNumber(req.totalGems)"></span>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="getVisibleBreakdown().length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">
                                Set a target level higher than your current level to see the detailed breakdown.
                            </td>
                        </tr>
                    </tbody>
                </table>
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
<script src="{{ asset('js/guild-level.js') }}"></script>
@endsection
