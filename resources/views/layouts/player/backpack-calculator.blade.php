@extends('layouts.app')
@section('title', "Backpack Calculator")
@section('meta_description', 'Track backpack upgrade gem costs and inventory expansion milestones for your Growtopia character.')

@section('content')
<div x-data="backpackCalculator()" @keydown.window.enter.prevent="
    let inputs = Array.from(document.querySelectorAll('input'));
    let idx = inputs.indexOf(document.activeElement);
    if(idx > -1) {
        if(idx < inputs.length - 1) {
            inputs[idx + 1].focus();
            if(inputs[idx + 1].select) inputs[idx + 1].select();
        } else {
            inputs[idx].blur();
        }
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
                    <img loading="lazy" src="{{ asset('images/items/backpack_upgrade.png') }}" alt="Backpack Upgrade" class="w-10 h-10 object-contain drop-shadow-sm">
                    Backpack Calculator
                    <span
                        class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Player Tools</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Find out exactly how many Gems you need to expand your inventory capacity.</p>

            </div>

            <!-- Quick Actions -->
            <div class="flex gap-3">
                <button @click="$store.modal.show('This will clear all your inputs and saved data.', () => { currentSlots = 16; targetSlots = 396; })"
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
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img loading="lazy" src="{{ asset('images/items/backpack_upgrade.png') }}" alt="Backpack Settings" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Slot Setup</h2>
                </div>

                <div class="space-y-6">
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Current Slots</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="16" max="386" step="10" x-model.lazy="currentSlots"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="16">
                            <span class="text-slate-500 font-bold text-[15px] ml-2 shrink-0">/ 386</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5 font-medium ml-1">Must be an interval like 16, 26, 36...</p>
                    </div>
                    
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Target Slots</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="26" max="396" step="10" x-model.lazy="targetSlots"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="396">
                            <span class="text-slate-500 font-bold text-[15px] ml-2 shrink-0">/ 396</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div
                class="bg-white rounded-[32px] p-8 lg:col-span-2 shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">

                <!-- Background Decoration -->
                <div
                    class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-blue-400/10 to-teal-400/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img loading="lazy" src="{{ asset('images/items/exclamation.png') }}" alt="Exclamation" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                        <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Upgrade Overview</h2>
                    </div>

                    <!-- 3-Item Stats Layout -->
                    <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Total Gems Needed -->
                        <div
                            class="bg-brand-50 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-700/50 rounded-[20px] p-5 flex flex-col justify-center items-center text-center shadow-sm relative overflow-hidden md:col-span-2">
                            <div class="absolute opacity-[0.05] blur-[1px] right-2 top-2">
                                <img loading="lazy" src="{{ asset('images/items/backpack_upgrade.png') }}" class="w-32 h-32 transform rotate-12">
                            </div>
                            <p class="text-[13px] font-bold text-brand-500 dark:text-brand-400 uppercase tracking-widest mb-1.5 relative z-10">Total Gems Needed
                            </p>
                            <div class="flex items-center gap-2 relative z-10">
                                <p class="text-[36px] lg:text-[42px] font-extrabold text-brand-700 dark:text-brand-300 leading-none" x-text="formatNumber(calculateTotalGemsNeeded())"></p>
                                <span class="bg-brand-100 text-brand-600 text-[13px] font-bold px-2 py-0.5 rounded-full">Gems</span>
                            </div>
                            <p class="text-[13px] text-brand-600/70 dark:text-brand-400/70 mt-2 font-medium relative z-10">To upgrade capacity from <span x-text="currentSlots"></span> to <span x-text="targetSlots"></span> slots</p>
                        </div>

                        <!-- Upgrades Needed -->
                        <div
                            class="bg-white border border-slate-200 dark:border-[#2a3449] rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-left">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Upgrades Needed
                            </p>
                            <p class="text-[24px] font-extrabold leading-none text-slate-800" x-text="calculateUpgradesNeeded()"></p>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">Purchases left to make</p>
                        </div>
                        
                        <!-- Immediate Next Upgrade Cost -->
                        <div
                            class="bg-white border border-slate-200 dark:border-[#2a3449] rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-left">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Next Upgrade Cost
                            </p>
                            <p class="text-[24px] font-extrabold leading-none text-slate-800" x-text="formatNumber(calculateGemsToNextUpgrade()) + ' Gems'"></p>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">For <span x-text="Number(currentSlots) + 10"></span> inventory capacity</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- The Table Area -->
        <div x-data="{ openTable: true }" class="glass-card rounded-[32px] overflow-hidden shadow-inner-light shadow-sm border border-slate-100">
            <!-- Accordion Header -->
            <button @click="openTable = !openTable" class="w-full flex items-center justify-between p-6 lg:px-8 bg-white/40 dark:bg-[#151b2b]/40 hover:bg-white/60 dark:hover:bg-[#151b2b]/60 transition-colors text-left focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img loading="lazy" src="{{ asset('images/items/scroll.png') }}" alt="Scroll Bulletin" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-800 tracking-tight">Backpack Upgrade Costs</h2>
                        <p class="text-[13px] text-slate-500 font-medium">Detailed scaling of slot prices to reach your target.</p>
                    </div>
                </div>
                <div class="p-2 rounded-full bg-slate-100 dark:bg-[#0b0f19] text-slate-500 transition-transform duration-300" :class="openTable ? 'rotate-180' : ''">
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
                 class="border-t border-slate-100 dark:border-[#1c2438] bg-white/30 dark:bg-[#151b2b]/30"
                 style="display: none;">
                
                <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-semibold tracking-wide text-xs">
                            <th class="px-4 py-3 uppercase">Upgrade No.</th>
                            <th class="px-4 py-3 uppercase">Backpack Slots</th>
                            <th class="px-4 py-3 uppercase">Price</th>
                            <th class="px-4 py-3 uppercase text-right">Cumulative Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(req, index) in getVisibleBreakdown()" :key="req.upgradeStep">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-[#111622]/50 transition-colors group bg-white dark:bg-[#151b2b]">
                                
                                <td class="px-4 py-3 font-bold text-slate-500">
                                    <span x-text="req.upgradeStep"></span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#0b0f19] text-slate-600 dark:text-slate-400 border-transparent dark:border-[#2a3449] font-bold flex items-center justify-center text-[11px] border border-slate-200 dark:border-[#2a3449] shadow-sm">
                                            <span x-text="req.slotsResult"></span>
                                        </div>
                                        <span class="font-bold text-slate-800">Slots Capacity</span>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 bg-brand-50 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300 px-3 py-1 rounded-lg font-bold text-[13px]">
                                        <span x-text="formatNumber(req.cost)"></span> Gems
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <span class="font-bold text-[14.5px] text-slate-800 block pt-1"
                                        x-text="formatNumber(req.cumulativePhaseCost) + ' Gems'"></span>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="getVisibleBreakdown().length === 0">
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500 font-medium">
                                Your current capacity meets or exceeds your target! No upgrades needed.
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
<script src="{{ asset('js/backpack-calc.js') }}?v=1.0.1"></script>
@endsection

