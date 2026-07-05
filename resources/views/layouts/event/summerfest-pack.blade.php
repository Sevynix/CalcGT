@extends('layouts.app')
@section('title', "SummerFest Pack Calculator")
@section('meta_description', 'Calculate SummerFest Pack costs, drops, and projected profit margins in Growtopia.')

@section('content')
<div x-data="calculator()" 
    @reset-calculator.window="resetCalculator()" 
    @reset-table.window="resetTable()"
    @keydown.window.enter.prevent="
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
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 flex flex-wrap items-center gap-2 sm:gap-3">
                    <img src="{{ asset('images/event/summer/fireworks.png') }}" alt="SummerFest"
                        class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 object-contain drop-shadow-sm shrink-0">
                    <span class="break-words leading-tight">SummerFest Pack</span>
                    <span
                        class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full uppercase tracking-wider shadow-sm shrink-0 whitespace-nowrap">Calculator</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Easily calculate your expected returns and profit margins from
                    breaking down Beach Blast Bingo packs.</p>

                <div
                    class="mt-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-200/60 dark:border-amber-700/50 rounded-xl p-3 flex items-start gap-2.5 text-amber-700 dark:text-amber-400 max-w-lg shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <p class="text-[13px] leading-relaxed font-medium">
                        <strong class="font-bold">Disclaimer:</strong> These projections assume 100% sell-through. Some
                        items might take longer to sell or may not sell at your target price, so use these estimates
                        wisely!
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex gap-3">
                <button @click="$store.modal.show('This will clear all your inputs and saved data.', () => $dispatch('reset-calculator'))"
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
            <div class="glass-card rounded-[32px] p-8 lg:col-span-1 shadow-sm border border-slate-100">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img src="{{ asset('images/event/summer/beach_blast.png') }}" alt="Beach Blast" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Pack Details</h2>
                </div>

                <div class="space-y-6">
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Number of Packs (20k Gems)</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="0" x-model.number="numPacks"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="1">
                            <span class="text-slate-500 font-bold text-[16px] ml-2">PACKS</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">WL Cost Per Pack</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="0" step="0.01" x-model.number="wlCostPerPack"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="0">
                            <span class="text-slate-500 font-bold text-[16px] ml-2">WL</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col pt-2 border-t border-slate-100">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Total Gems Needed</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <img src="{{ asset('images/items/gems.png') }}" alt="Gems" class="w-5 h-5 object-contain mr-2" onerror="this.style.display='none'">
                            <input type="number" min="0" step="1000" x-model.number="gemsInput"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="20000">
                            <span class="text-slate-500 font-bold text-[16px] ml-2">GEMS</span>
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
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img src="{{ asset('images/lock/worldlock.png') }}" alt="WL" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                        <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Profit Overview</h2>
                    </div>

                    <!-- 2x2 Symmetrical Grid -->
                    <div class="flex-grow grid grid-cols-2 gap-4">

                        <!-- Cost -->
                        <div class="bg-slate-50 border border-slate-100 rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 flex flex-col justify-center overflow-hidden">
                            <p class="text-[11px] sm:text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Cost</p>
                            <p class="text-[18px] sm:text-[24px] font-bold text-slate-800 break-words" x-text="formatTotalCost()"></p>
                        </div>

                        <!-- Revenue -->
                        <div class="bg-slate-50 border border-slate-100 rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 flex flex-col justify-center overflow-hidden">
                            <p class="text-[11px] sm:text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Revenue</p>
                            <p class="text-[18px] sm:text-[24px] font-bold text-slate-800 break-words" x-text="formatTotalRevenue()"></p>
                        </div>

                        <!-- Net Profit -->
                        <div class="rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 text-white shadow-md flex flex-col justify-center transform transition hover:-translate-y-1 overflow-hidden"
                            :class="netProfit > 0 ? 'bg-emerald-500' : (netProfit < 0 ? 'bg-red-500' : 'bg-slate-800')">
                            <p class="text-[11px] sm:text-[12px] font-bold text-white/70 uppercase tracking-widest mb-1.5">Net Profit</p>
                            <p class="text-[20px] sm:text-[28px] font-extrabold leading-tight sm:leading-none break-words" x-text="formatNetProfit()"></p>
                        </div>

                        <!-- Margin -->
                        <div class="bg-white border border-slate-200 dark:border-[#2a3449] rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-center overflow-hidden">
                            <p class="text-[11px] sm:text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Margin</p>
                            <p class="text-[20px] sm:text-[28px] font-extrabold leading-tight sm:leading-none break-words"
                                :class="netProfit > 0 ? 'text-emerald-500' : (netProfit < 0 ? 'text-red-500' : 'text-slate-800 dark:text-slate-200')"
                                x-text="profitPercentage"></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- The Table Area -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-inner-light shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100 bg-white/40 dark:bg-[#151b2b]/40 flex flex-wrap justify-between items-center gap-3">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img src="{{ asset('images/items/forsale.png') }}" alt="For Sale" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-800 tracking-tight">Sell Price</h2>
                        <p class="text-[13px] text-slate-500 font-medium">Manually input your stock and set prices to calculate margins.</p>
                    </div>
                </div>
                <button @click="$store.modal.show('This will clear all prices and stock values.', () => $dispatch('reset-table'), 'table')"
                    class="bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white px-4 py-2 rounded-xl font-bold transition shadow-sm text-sm flex items-center gap-2 active:scale-95 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Reset Table
                </button>
            </div>

            <!-- ================================ -->
            <!-- DESKTOP: Table layout (>= md)    -->
            <!-- ================================ -->
            <div class="hidden md:block w-full overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-semibold tracking-wide text-xs">
                            <th class="px-4 py-3 uppercase">Item</th>
                            <th class="px-4 py-3 uppercase">Stock</th>
                            <th class="px-4 py-3 uppercase">Sell Price</th>
                            <th class="px-4 py-3 uppercase">Sales Type</th>
                            <th class="px-4 py-3 uppercase text-right">Projected Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(item, key) in items" :key="key">
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <!-- Tool Name & Icon -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center p-1.5">
                                            <img :src="asset(item.icon)" :alt="item.name"
                                                class="w-full h-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                                        </div>
                                        <span class="font-bold text-slate-800" x-text="item.name"></span>
                                    </div>
                                </td>

                                <!-- Stock Input -->
                                <td class="px-4 py-3">
                                    <div class="relative max-w-[100px]">
                                        <input type="number" min="0" x-model.number="stocks[key]"
                                            class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-800 text-center font-bold px-3 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"
                                            placeholder="0">
                                    </div>
                                </td>

                                <!-- Price Input -->
                                <td class="px-4 py-3">
                                    <div class="relative max-w-[120px]">
                                        <input type="number" min="0" step="0.01" x-model.number="prices[key]"
                                            class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-800 text-center font-bold px-3 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"
                                            :placeholder="item.type === 'per wl' ? 'Items/WL' : (item.type === '200/?' ? 'WL/200' : 'WL/Item')">
                                    </div>
                                </td>

                                <!-- Select Type -->
                                <td class="px-4 py-3">
                                    <div class="relative inline-block w-full">
                                        <select x-model="item.type"
                                            class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-600 font-medium px-3 py-2 pr-8 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer appearance-none">
                                            <option value="per wl">Items per WL</option>
                                            <option value="200/?">WL per 200 items</option>
                                            <option value="wl/item">WL per 1 item</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </td>

                                <!-- Final Revenue for row -->
                                <td class="px-4 py-3 text-right">
                                    <span class="font-bold text-[15px] text-emerald-600 block pt-1"
                                        x-text="getItemRevenue(key) + ' WL'"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- ================================ -->
            <!-- MOBILE: Card layout (< md)       -->
            <!-- ================================ -->
            <div class="md:hidden divide-y divide-slate-100">
                <template x-for="(item, key) in items" :key="key">
                    <div class="p-4 flex flex-col gap-3 hover:bg-slate-50/50 transition-colors">
                        <!-- Item name + revenue -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex-shrink-0 flex items-center justify-center p-1.5">
                                    <img :src="asset(item.icon)" :alt="item.name"
                                        class="w-full h-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                                </div>
                                <span class="font-bold text-slate-800 text-sm leading-tight" x-text="item.name"></span>
                            </div>
                            <span class="font-bold text-[15px] text-emerald-600 shrink-0 ml-2" x-text="getItemRevenue(key) + ' WL'"></span>
                        </div>
                        <!-- Inputs row -->
                        <div class="grid grid-cols-3 gap-2 mt-1">
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Stock</label>
                                <input type="number" min="0" x-model.number="stocks[key]"
                                    class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-800 text-center font-bold px-2 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition text-sm h-[38px]"
                                    placeholder="0">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Price</label>
                                <input type="number" min="0" step="0.01" x-model.number="prices[key]"
                                    class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-800 text-center font-bold px-2 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition text-sm h-[38px]"
                                    :placeholder="item.type === 'per wl' ? 'Items/WL' : (item.type === '200/?' ? 'WL/200' : 'WL/Item')">
                            </div>
                            <div class="flex flex-col gap-1 relative">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Type</label>
                                <select x-model="item.type"
                                    class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-600 font-medium px-1.5 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer text-[13px] appearance-none h-[38px]">
                                    <option value="per wl">Items/WL</option>
                                    <option value="200/?">WL/200</option>
                                    <option value="wl/item">WL/Item</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-1 top-4 flex items-center px-1 text-slate-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Profit Comparison Analysis -->
        <div class="glass-card rounded-[32px] p-8 shadow-sm border border-slate-100 mt-8">
            <div class="flex items-start gap-4 mb-8" x-data="{ showHowWorksModal: false, howWorksLang: 'en' }">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-[#0b0f19] flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                    <img src="{{ asset('images/items/exclamation.png') }}" alt="Path Analysis" class="w-8 h-8 object-contain drop-shadow-sm">
                </div>
                <div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Path Profit Analysis</h2>
                    <p class="text-[13px] text-slate-500 font-medium mt-0.5 mb-2.5">Compare profits across different item combinations.</p>
                    
                    <button @click="showHowWorksModal = true" class="btn-guide-hover relative group flex items-center gap-1.5 active:scale-95 w-fit cursor-pointer mt-1">
                        <span class="btn-guide-dot"></span>
                        <svg class="btn-guide-icon shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>Important Guide</span>
                    </button>
                </div>

                <!-- How it works Modal -->
                <style>
                    .btn-guide-hover {
                        font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 9999px; transition: all 0.3s;
                        background-color: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                    }
                    .btn-guide-icon { width: 16px; height: 16px; color: #3b82f6; transition: color 0.3s; }
                    .btn-guide-hover:hover { 
                        background-color: #3b82f6 !important; color: #ffffff !important; border-color: #3b82f6 !important;
                        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
                    }
                    .btn-guide-hover:hover .btn-guide-icon { color: #ffffff !important; }
                    
                    .dark .btn-guide-hover {
                        background-color: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); color: #60a5fa;
                    }
                    .dark .btn-guide-icon { color: #3b82f6; }
                    .dark .btn-guide-hover:hover {
                        background-color: #3b82f6 !important; color: #ffffff !important; border-color: #3b82f6 !important;
                        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
                    }
                    .dark .btn-guide-hover:hover .btn-guide-icon { color: #ffffff !important; }
                    
                    .btn-guide-dot {
                        position: absolute; top: -2px; right: -2px; width: 10px; height: 10px; background-color: #ef4444; border-radius: 9999px; box-shadow: 0 0 0 3px #eff6ff;
                    }
                    .dark .btn-guide-dot { box-shadow: 0 0 0 3px #0f172a; }
                    @keyframes wn-float {
                        0% { transform: translateY(0px); }
                        50% { transform: translateY(-8px); }
                        100% { transform: translateY(0px); }
                    }
                    @keyframes wn-float-slow {
                        0% { transform: translateY(0px); }
                        50% { transform: translateY(-6px); }
                        100% { transform: translateY(0px); }
                    }
                </style>
                <template x-teleport="body">
                    <div x-show="showHowWorksModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                        <!-- Backdrop -->
                        <div x-show="showHowWorksModal" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                             class="fixed inset-0" style="background:rgba(15,23,42,0.75); backdrop-filter:blur(8px);" @click="showHowWorksModal = false"></div>
                             
                        <!-- Modal Content -->
                        <div x-show="showHowWorksModal" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                             class="relative w-full max-w-xl flex flex-col bg-white dark:bg-[#0f172a]" style="max-width:460px; border-radius:28px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); overflow:hidden; max-height:90vh;">
                            
                            <!-- Simple Header -->
                            <div style="padding:24px 24px 20px; background:linear-gradient(135deg, #1e3a8a, #2563eb); text-align:center; position:relative;">
                                <!-- Close Button -->
                                <button @click="showHowWorksModal = false" style="position:absolute; top:16px; right:16px; z-index:50; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                
                                <h3 style="margin:0; font-size:22px; font-weight:800; color:#fff; line-height:1.2; letter-spacing:-0.02em;">
                                    HOW IT WORKS
                                </h3>
                            </div>
                            
                            <!-- Body -->
                            <div style="padding:24px; overflow-y:auto;" class="custom-scrollbar bg-white dark:bg-[#0f172a]">
                                
                                <!-- Premium Sliding Language Toggle -->
                                <style>
                                    .lang-toggle-wrapper { display: flex; justify-content: center; margin-bottom: 32px; }
                                    .lang-toggle-container { position: relative; display: flex; width: 260px; padding: 6px; background-color: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); }
                                    .dark .lang-toggle-container { background-color: #0b0f19; border-color: #1c2438; }
                                    .lang-toggle-slider { position: absolute; top: 6px; bottom: 6px; left: 6px; width: 124px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1); }
                                    .dark .lang-toggle-slider { background-color: #1e293b; border-color: #35415a; box-shadow: 0 2px 8px rgba(0,0,0,0.4); }
                                    .lang-toggle-btn { position: relative; z-index: 10; width: 50%; padding: 8px 0; font-size: 13px; font-weight: 800; letter-spacing: 0.02em; cursor: pointer; background: transparent; border: none; transition: color 0.3s; color: #94a3b8; }
                                    .lang-toggle-btn.active { color: #1d4ed8; }
                                    .dark .lang-toggle-btn { color: #64748b; }
                                    .dark .lang-toggle-btn.active { color: #60a5fa; }
                                    .lang-toggle-btn:hover:not(.active) { color: #475569; }
                                    .dark .lang-toggle-btn:hover:not(.active) { color: #94a3b8; }
                                </style>
                                <div class="lang-toggle-wrapper">
                                    <div class="lang-toggle-container">
                                        <div class="lang-toggle-slider" :style="howWorksLang === 'id' ? 'transform: translateX(124px);' : 'transform: translateX(0);'"></div>
                                        <button @click="howWorksLang = 'en'" class="lang-toggle-btn" :class="howWorksLang === 'en' ? 'active' : ''">English</button>
                                        <button @click="howWorksLang = 'id'" class="lang-toggle-btn" :class="howWorksLang === 'id' ? 'active' : ''">Indonesian</button>
                                    </div>
                                </div>
                                
                                <!-- Content EN -->
                                <div x-show="howWorksLang === 'en'">
                                    
                                    <!-- Section 1 -->
                                    <div>
                                        <h4 style="margin:0 0 28px; font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; display:flex; align-items:center; gap:8px;">
                                            Path Profit Analysis
                                            <div class="h-px flex-1 bg-slate-200 dark:bg-[#2a3449]"></div>
                                        </h4>
                                        <div style="display:flex; flex-direction:column; gap:12px;">
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">1</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed">The path profit analysis method works by inputting the price of each item.</p>
                                            </div>

                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">2</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed">By inputting the item prices, the best method for profit will be calculated and displayed.</p>
                                            </div>

                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">3</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed">Make sure the prices you input match the in-game or Discord prices for accurate results.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 2 -->
                                    <div style="margin-top:48px;">
                                        <h4 style="margin:0 0 28px; font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; display:flex; align-items:center; gap:8px;">
                                            System Conversions
                                            <div class="h-px flex-1 bg-slate-200 dark:bg-[#2a3449]"></div>
                                        </h4>
                                        <div style="display:flex; flex-direction:column; gap:12px;">
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">1</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed">An average of <strong class="text-slate-900 dark:text-slate-100 font-bold">100 Fireworks</strong> can be used to get <strong class="text-slate-900 dark:text-slate-100 font-bold">1 Placed Summer Surprise</strong>.</p>
                                            </div>
                                            
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">2</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed"><strong class="text-slate-900 dark:text-slate-100 font-bold">20 Summer Surprises</strong> (or Placed) can be destroyed to claim <strong class="text-slate-900 dark:text-slate-100 font-bold">1 Super Summer Surprise</strong>.</p>
                                            </div>
                                            
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">3</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed"><strong class="text-slate-900 dark:text-slate-100 font-bold">5 Surprises</strong> + <strong class="text-slate-900 dark:text-slate-100 font-bold">100 Fireworks</strong> + <strong class="text-slate-900 dark:text-slate-100 font-bold">10 Sand</strong> = <strong class="text-slate-900 dark:text-slate-100 font-bold">1 Summer Artifact Chest</strong>.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Content ID -->
                                <div x-show="howWorksLang === 'id'" style="display:none;">
                                    
                                    <!-- Section 1 -->
                                    <div>
                                        <h4 style="margin:0 0 28px; font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; display:flex; align-items:center; gap:8px;">
                                            Analisis Path Profit
                                            <div class="h-px flex-1 bg-slate-200 dark:bg-[#2a3449]"></div>
                                        </h4>
                                        <div style="display:flex; flex-direction:column; gap:12px;">
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">1</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed">Metode path profit analisis ini berfungsi dengan cara memasukkan input harga dari setiap item.</p>
                                            </div>

                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">2</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed">Dengan menginput harga item maka akan muncul metode terbaik untuk mendapatkan profit.</p>
                                            </div>

                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">3</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed">Pastikan harga yang anda input sesuai dengan harga in-game/discord agar hasil lebih akurat dan realistis.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 2 -->
                                    <div style="margin-top:48px;">
                                        <h4 style="margin:0 0 28px; font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; display:flex; align-items:center; gap:8px;">
                                            Sistem Konversi
                                            <div class="h-px flex-1 bg-slate-200 dark:bg-[#2a3449]"></div>
                                        </h4>
                                        <div style="display:flex; flex-direction:column; gap:12px;">
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">1</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed">Rata-rata <strong class="text-slate-900 dark:text-slate-100 font-bold">100 Fireworks</strong> dapat digunakan untuk mendapatkan <strong class="text-slate-900 dark:text-slate-100 font-bold">1 Placed Summer Surprise</strong>.</p>
                                            </div>
                                            
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">2</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed"><strong class="text-slate-900 dark:text-slate-100 font-bold">20 Summer Surprises</strong> (atau Placed) dapat dihancurkan dan claim <strong class="text-slate-900 dark:text-slate-100 font-bold">1 Super Summer Surprise</strong>.</p>
                                            </div>
                                            
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-2xl p-4">
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 shadow-inner border border-blue-100 dark:border-blue-800/50">
                                                    <span class="text-[16px] font-extrabold text-blue-600 dark:text-blue-400">3</span>
                                                </div>
                                                <p class="text-[13px] text-slate-700 dark:text-slate-300 font-medium leading-relaxed"><strong class="text-slate-900 dark:text-slate-100 font-bold">5 Surprises</strong> + <strong class="text-slate-900 dark:text-slate-100 font-bold">100 Fireworks</strong> + <strong class="text-slate-900 dark:text-slate-100 font-bold">10 Sand</strong> = <strong class="text-slate-900 dark:text-slate-100 font-bold">1 Summer Artifact Chest</strong>.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Footer Button -->
                                <div style="margin-top:24px;">
                                    <button @click="showHowWorksModal = false" style="display:flex; align-items:center; justify-content:center; gap:8px; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-size:14px; font-weight:700; padding:14px; border-radius:14px; border:none; cursor:pointer; box-shadow:0 4px 14px rgba(37,99,235,0.35); width:100%; transition:all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(37,99,235,0.4)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 14px rgba(37,99,235,0.35)';">
                                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span x-text="howWorksLang === 'en' ? 'Got it, thanks!' : 'Mengerti, terima kasih!'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Input Table for ALL items -->
            <div class="glass-card rounded-[24px] overflow-hidden shadow-sm border border-slate-100 mb-8">
                <!-- Desktop View -->
                <div class="hidden md:block w-full overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-semibold tracking-wide text-xs">
                                <th class="px-4 py-3 uppercase">Item</th>
                                <th class="px-4 py-3 uppercase">Sell Price</th>
                                <th class="px-4 py-3 uppercase">Sales Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <template x-for="itemId in ['pss', 'sss', 'ac', 'sand']" :key="itemId">
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-50 shadow-sm border border-slate-100 flex items-center justify-center p-1.5">
                                                <img :src="asset(pathItems[itemId].icon)" :alt="pathItems[itemId].name" class="w-full h-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                                            </div>
                                            <span class="font-bold text-slate-800" x-text="pathItems[itemId].name"></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="relative max-w-[120px]">
                                            <input type="number" min="0" step="0.01" x-model.number="comparePrices[itemId]" class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-800 text-center font-bold px-3 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition" placeholder="0">
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="relative inline-block w-full max-w-[160px]">
                                            <select x-model="compareTypes[itemId]" class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-600 font-medium px-3 py-2 pr-8 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer appearance-none">
                                                <option value="wl/item">WL per 1 item</option>
                                                <option value="per wl">Items per WL</option>
                                                <option value="200/?">WL per 200 items</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden divide-y divide-slate-100 bg-white">
                    <template x-for="itemId in ['pss', 'sss', 'ac', 'sand']" :key="itemId">
                        <div class="p-4 flex flex-col gap-3 hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 shadow-sm border border-slate-100 flex-shrink-0 flex items-center justify-center p-1.5">
                                        <img :src="asset(pathItems[itemId].icon)" :alt="pathItems[itemId].name" class="w-full h-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                                    </div>
                                    <span class="font-bold text-slate-800 text-sm leading-tight" x-text="pathItems[itemId].name"></span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-1">
                                <div class="flex flex-col gap-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Price</label>
                                    <input type="number" min="0" step="0.01" x-model.number="comparePrices[itemId]" class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-800 text-center font-bold px-2 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition text-sm h-[38px]" placeholder="0">
                                </div>
                                <div class="flex flex-col gap-1 relative">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Type</label>
                                    <select x-model="compareTypes[itemId]" class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-600 font-medium px-1.5 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer text-[13px] appearance-none h-[38px]">
                                        <option value="wl/item">WL/Item</option>
                                        <option value="per wl">Items/WL</option>
                                        <option value="200/?">WL/200</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-1 top-4 flex items-center px-1 text-slate-400">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- All Paths Comparison Table -->
            <div class="bg-white border border-slate-200 dark:border-[#2a3449] rounded-[24px] overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 dark:bg-[#151b2b]/40 flex flex-wrap justify-between items-center gap-3">
                    <div>
                        <h3 class="text-[18px] font-extrabold text-slate-800 tracking-tight">Comparison Results</h3>
                        <p class="text-[13px] text-slate-500 font-medium">All paths calculated based on your input above.</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[12px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Base Cost</span>
                        <span class="font-bold text-[16px] text-red-500" x-text="formatTotalCost()"></span>
                    </div>
                </div>

                <div class="mt-6 space-y-6">
                    <template x-for="path in sortedPaths" :key="path.id">
                        <div x-data="{ expanded: false }" class="glass-card rounded-[32px] shadow-sm border border-slate-100 flex flex-col relative overflow-hidden group transition-all duration-300 hover:border-slate-300 mb-6"
                             :class="path._rank === 1 ? 'ring-2 ring-amber-400/50 shadow-amber-100/50' : ''">
                            
                            <!-- Card Header -->
                            <button @click="expanded = !expanded" class="w-full text-left focus:outline-none bg-white/40 dark:bg-[#151b2b]/40 hover:bg-white/60 dark:hover:bg-[#151b2b]/60 transition-colors cursor-pointer p-6 lg:p-8 relative"
                                    :class="expanded ? 'border-b border-slate-100 dark:border-[#1c2438]' : ''">
                                
                                <!-- Header Area (always visible) -->
                                <div class="w-full flex items-center justify-between">
                                    <!-- Title Left -->
                                    <div class="flex items-center gap-3 sm:gap-4">
                                        <div x-show="path._rank === 1" class="w-10 h-10 sm:w-12 sm:h-12 rounded-[14px] sm:rounded-2xl flex items-center justify-center shrink-0 shadow-inner border" style="background: linear-gradient(to right, #fbbf24, #f59e0b); border-color: #f59e0b;">
                                            <span class="font-extrabold text-[15px] sm:text-[18px] text-white drop-shadow-sm" x-text="'#' + path._rank"></span>
                                        </div>
                                        <div x-show="path._rank > 1" class="w-10 h-10 sm:w-12 sm:h-12 rounded-[14px] sm:rounded-2xl bg-slate-50 dark:bg-[#0b0f19] flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                                            <span class="text-slate-400 font-extrabold text-[15px] sm:text-[18px]" x-text="'#' + path._rank"></span>
                                        </div>
                                        
                                        <div class="flex flex-col pr-1 sm:pr-0">
                                            <h2 class="text-[15px] sm:text-[20px] lg:text-[24px] font-extrabold text-slate-800 dark:text-slate-200 tracking-tight leading-tight sm:leading-snug" x-text="path.label"></h2>
                                            
                                            <!-- Minimized small info text -->
                                            <div x-show="!expanded" class="flex flex-wrap items-center gap-2 mt-1 lg:mt-2" style="display: none;">
                                                <div class="bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400 text-[11px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 border border-slate-200 dark:border-[#2a3449]/50">
                                                    REVENUE: <span class="text-slate-700 dark:text-slate-300" x-text="formatPathRevenue(path)"></span>
                                                </div>
                                                <div class="text-[11px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 border"
                                                     :class="getPathProfit(path) > 0 ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-700/50' : (getPathProfit(path) < 0 ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-100 dark:border-red-700/50' : 'bg-slate-50 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400 border-slate-200/50 dark:border-[#2a3449]/50')">
                                                    NET PROFIT: <span x-text="formatPathProfit(path)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Accordion Arrow -->
                                    <div class="p-2 rounded-full bg-slate-100 dark:bg-[#0b0f19] text-slate-500 transition-transform duration-300 shrink-0 ml-4" :class="expanded ? 'rotate-180' : ''">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </button>
                            
                            <!-- Card Body -->
                            <div x-show="expanded"
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="opacity-0 -translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200 transform"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-4"
                                 class="p-6 lg:p-8 bg-white/30 dark:bg-[#151b2b]/30"
                                 style="display: none;">
                                 
                                <!-- Big Profit Overview Boxes (Visible only when expanded) -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 w-full mb-8">
                                    <div class="bg-slate-50 dark:bg-[#0b0f19] border border-slate-100 dark:border-[#1c2438] rounded-[20px] px-5 flex flex-col justify-center text-right" style="padding-top: 14px; padding-bottom: 14px;">
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Revenue</p>
                                        <p class="text-[20px] sm:text-[22px] font-extrabold text-slate-800 dark:text-slate-200 leading-none truncate" x-text="formatPathRevenue(path)"></p>
                                    </div>
                                    <div class="rounded-[20px] px-5 shadow-md flex flex-col justify-center text-right text-white transform transition hover:-translate-y-1 border border-transparent"
                                        :class="getPathProfit(path) > 0 ? 'bg-emerald-500' : (getPathProfit(path) < 0 ? 'bg-red-500' : 'bg-slate-800')" style="padding-top: 14px; padding-bottom: 14px;">
                                        <p class="text-[11px] font-bold text-white/70 uppercase tracking-widest mb-1">Net Profit</p>
                                        <p class="text-[20px] sm:text-[22px] font-extrabold leading-none truncate" x-text="formatPathProfit(path)"></p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 w-full">
                                
                                <!-- Left Column: How it works -->
                                <div class="xl:col-span-5">
                                    <div class="bg-slate-50 dark:bg-[#0b0f19] border border-slate-100 dark:border-[#1c2438] rounded-[24px] p-6 h-full flex flex-col">
                                        <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            How this method works
                                        </p>
                                        <ul class="relative ml-2 space-y-5">
                                            <div class="absolute top-2 bottom-2 bg-slate-200 dark:bg-[#2a3449]" style="left: 7px; width: 2px;" x-show="getPathExplanations(path.id).length > 1"></div>
                                            <template x-for="(step, index) in getPathExplanations(path.id)" :key="index">
                                                <li class="text-[13px] text-slate-600 dark:text-slate-400 flex items-start gap-4 relative z-10">
                                                    <div class="w-4 h-4 rounded-full bg-white dark:bg-[#151b2b] border-2 border-slate-300 dark:border-slate-600 flex shrink-0 mt-0.5 shadow-sm"></div>
                                                    <span class="leading-relaxed font-medium" x-text="step"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                
                                <!-- Right Column: Visual Sell Breakdown -->
                                <div class="xl:col-span-7">
                                    <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2 px-1">
                                        <img src="{{ asset('images/items/exclamation.png') }}" alt="Exclamation" class="w-4 h-4 object-contain">
                                        Final Output & Profit Breakdown
                                    </p>
                                    <div class="flex flex-col gap-3">
                                        <template x-for="(item, idx) in getSellBreakdown(path.id)" :key="idx">
                                            <div class="flex flex-col">
                                                <div class="flex items-center justify-between p-3 sm:p-4 rounded-[20px] border border-slate-100 dark:border-[#1c2438] bg-white dark:bg-[#151b2b] shadow-sm hover:bg-slate-50 dark:hover:bg-[#111622] transition gap-x-3 gap-y-2"
                                                     :class="item.isCost ? 'border-red-100 dark:border-red-900/30 bg-red-50/30 dark:bg-red-900/10' : ''">
                                                    
                                                    <!-- Left: Source & Main -->
                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 flex-1 min-w-0">
                                                        
                                                        <!-- Source Block -->
                                                        <template x-if="item.sourceIcon">
                                                             <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 sm:gap-1.5 shrink-0">
                                                                 <!-- Source Badge -->
                                                                 <div class="flex items-center gap-1.5 px-2 py-1 bg-slate-100 dark:bg-[#0b0f19] rounded-md border border-slate-200 dark:border-[#2a3449] w-fit">
                                                                     <img :src="asset(item.sourceIcon)" class="w-3.5 h-3.5 sm:w-4 sm:h-4 drop-shadow-sm">
                                                                     <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400" x-text="item.sourceText"></span>
                                                                 </div>
                                                                 <!-- Desktop Arrow -->
                                                                 <svg class="hidden sm:block w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                                 </svg>
                                                                 <!-- Mobile Arrow -->
                                                                 <div class="sm:hidden flex pl-5">
                                                                     <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" style="transform: rotate(90deg);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                                     </svg>
                                                                 </div>
                                                             </div>
                                                        </template>
                                                        
                                                        <!-- Main Item -->
                                                        <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0 mt-0.5 sm:mt-0">
                                                            <!-- Icon -->
                                                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-50 dark:bg-[#0b0f19] flex items-center justify-center border border-slate-100 dark:border-[#1c2438] shrink-0">
                                                                <img :src="asset(item.icon)" class="w-6 h-6 sm:w-7 sm:h-7 object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                                                            </div>
                                                            
                                                            <!-- Name and Qty -->
                                                            <div class="flex flex-col flex-1 min-w-0">
                                                                <div class="text-[12px] sm:text-[13px] font-bold text-slate-800 dark:text-slate-200 leading-tight mb-0.5 break-words">
                                                                    <span x-text="item.qty + 'x '"></span><span x-text="item.name"></span>
                                                                </div>
                                                                <div class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-tight break-words" x-text="item.valStr"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Right: Revenue -->
                                                    <div class="shrink-0 ml-1 sm:ml-2">
                                                        <div class="inline-block text-right bg-slate-50 sm:bg-white dark:bg-[#0b0f19] px-2 py-1.5 sm:px-2.5 sm:py-1.5 rounded-lg border border-slate-100 dark:border-[#1c2438] shadow-sm">
                                                            <span class="text-[12px] sm:text-[14px] font-extrabold" 
                                                                  :class="item.isCost ? 'text-red-500' : 'text-emerald-600 dark:text-emerald-400'" 
                                                                  x-text="(item.isCost ? '-' : '+') + item.rev + ' WL'"></span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div x-show="item.silentZeroWarning" class="mt-1.5 mx-2 text-[10px] text-amber-600 bg-amber-50 rounded-lg p-2 border border-amber-200/60 shadow-sm flex items-start gap-1.5" style="display: none;">
                                                    <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    <span class="leading-tight">Tidak terjual (<span class="font-bold" x-text="item.qty"></span> item). Nilainya terlalu kecil (dibawah 1 WL).</span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                            </div>
                            </div>
                        </div>
                    </template>
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
<script src="{{ asset('js/summerfest-pack.js') }}?v=1.0.1"></script>
@endsection

