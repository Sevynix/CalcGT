@extends('layouts.app')
@section('title', "Pinata Value Pack Calculator")
@section('meta_description', "Calculate Pinata Value Pack costs, drops, and projected profit margins in Growtopia.")

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
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 flex flex-wrap items-center gap-2 sm:gap-3">
                    <img src="{{ asset('images/event/cinco/whistle.png') }}" alt="Cinco De Mayo"
                        class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 object-contain drop-shadow-sm shrink-0">
                    <span class="break-words leading-tight">Pinata Value Pack</span>
                    <span
                        class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full uppercase tracking-wider shadow-sm shrink-0 whitespace-nowrap">Calculator</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Easily calculate your expected returns and profit margins from
                    breaking down Pinata Value Packs.</p>

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
            <div class="glass-card rounded-[32px] p-8 lg:col-span-1 shadow-sm border border-slate-100">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img src="{{ asset('images/event/cinco/block_de_mayo.png') }}" alt="Block De Mayo" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Pack Details</h2>
                </div>

                <div class="space-y-6">
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Number of Packs (30k Gems)</label>
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
                                placeholder="30000">
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
                    class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-amber-400/10 to-orange-400/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none">
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
                <button @click="$store.modal.show('This will clear all prices and stock values.', () => resetTable(), 'table')"
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

    </div>
</div>
@endsection

@section('scripts')
<script>
    function asset(path) {
        return path.startsWith('/') ? path : '/' + path;
    }
</script>
<script src="{{ asset('js/cinco-pack.js') }}"></script>
@endsection
