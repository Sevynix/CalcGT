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
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-[#0b0f19] flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                    <img src="{{ asset('images/items/exclamation.png') }}" alt="Path Analysis" class="w-8 h-8 object-contain drop-shadow-sm">
                </div>
                <div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Path Profit Analysis</h2>
                    <p class="text-[13px] text-slate-500 font-medium">Compare profits across different item combinations.</p>
                </div>
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

            <!-- How it works card -->
            <div class="glass-card border border-slate-200 dark:border-[#2a3449] rounded-[24px] overflow-hidden shadow-sm mt-8">
                <div class="p-6 border-b border-slate-100 dark:border-[#1c2438] bg-slate-50/50 dark:bg-[#151b2b]/40 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-800/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[18px] font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">How System Conversions Work</h3>
                        <p class="text-[13px] text-slate-500 dark:text-slate-400 font-medium">Understanding the item yields and crafting rules.</p>
                    </div>
                </div>
                <div class="p-6 md:p-8 bg-white dark:bg-transparent">
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1c2438] flex items-center justify-center shrink-0 mt-0.5 text-slate-400 dark:text-slate-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                            <p class="text-[14px] text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                                An average of <strong class="text-slate-800 dark:text-slate-200">100 Fireworks</strong> can be used to get <strong class="text-slate-800 dark:text-slate-200">1 Placed Summer Surprise</strong>.
                            </p>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1c2438] flex items-center justify-center shrink-0 mt-0.5 text-slate-400 dark:text-slate-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                            <p class="text-[14px] text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                                <strong class="text-slate-800 dark:text-slate-200">20 Summer Surprises</strong> (or Placed Summer Surprises) can be broken into <strong class="text-slate-800 dark:text-slate-200">1 Super Summer Surprise</strong>.
                            </p>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1c2438] flex items-center justify-center shrink-0 mt-0.5 text-slate-400 dark:text-slate-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                            <p class="text-[14px] text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                                <strong class="text-slate-800 dark:text-slate-200">5 Summer Surprises</strong> + <strong class="text-slate-800 dark:text-slate-200">100 Fireworks</strong> + <strong class="text-slate-800 dark:text-slate-200">10 Sand</strong> = <strong class="text-slate-800 dark:text-slate-200">1 Summer Artifact Chest</strong>.
                            </p>
                        </li>
                    </ul>
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
<script src="{{ asset('js/summerfest-pack.js') }}"></script>
@endsection
