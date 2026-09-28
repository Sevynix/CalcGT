@extends('layouts.app')
@section('title', "Shatter Crystal / Harmonizing Crystal Calculator")
@section('meta_description', 'Calculate your profit from harmonizing crystals in Growtopia. Input crystal prices and item sell prices to see your profit margin.')

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
                <a href="/" class="inline-flex items-center text-slate-500 hover:text-brand-600 transition font-medium mb-3 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Dashboard
                </a>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 flex flex-wrap items-center gap-2 sm:gap-3">
                    <img src="{{ asset('images/carnival/shatter/2246.png') }}" alt="Crystal" loading="lazy" class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 object-contain drop-shadow-sm shrink-0">
                    <span class="break-words leading-tight">Shatter Crystal</span>
                    <span class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full uppercase tracking-wider shadow-sm shrink-0 whitespace-nowrap">Calculator</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Calculate your profit from harmonizing crystals. Set crystal prices, select an item recipe, then enter sell prices.</p>
                <div class="mt-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-200/60 dark:border-amber-700/50 rounded-xl p-3 flex items-start gap-2.5 text-amber-700 dark:text-amber-400 max-w-lg shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-[13px] leading-relaxed font-medium">
                        <strong class="font-bold">Disclaimer:</strong> These projections assume 100% sell-through. Some items might take longer to sell or may not sell at your target price, so use these estimates wisely!
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <button @click="$store.modal.show('This will clear all your inputs and saved data.', () => resetCalculator())" class="bg-red-500 hover:bg-red-600 active:bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-md text-sm flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset All
                </button>
            </div>
        </div>

        <!-- Crystal Prices Table -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-inner-light shadow-sm border border-slate-100 dark:border-[#1c2438] mb-6">
            <div class="p-6 border-b border-slate-100 dark:border-[#1c2438] bg-white/40 dark:bg-[#151b2b]/40 flex flex-wrap justify-between items-center gap-3">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-[#111622] flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img src="{{ asset('images/carnival/shatter/2246.png') }}" alt="Crystal" loading="lazy" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Crystal Prices</h2>
                        <p class="text-[13px] text-slate-500 dark:text-slate-400 font-medium">Input the current market prices for harmonizing crystals.</p>
                    </div>
                </div>
            </div>
            <div class="hidden md:block w-full overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-[#111622] text-slate-500 dark:text-slate-400 font-semibold tracking-wide text-xs">
                            <th class="px-6 py-4 uppercase">Item</th>
                            <th class="px-6 py-4 uppercase">Cost (WL)</th>
                            <th class="px-6 py-4 uppercase">Sales Type</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-[#151b2b]">
                        @php
                            $crystals = [
                                ['key'=>'red',   'label'=>'Red Crystal',   'img'=>'2242'],
                                ['key'=>'green', 'label'=>'Green Crystal', 'img'=>'2244'],
                                ['key'=>'blue',  'label'=>'Blue Crystal',  'img'=>'2246'],
                                ['key'=>'white', 'label'=>'White Crystal', 'img'=>'2248'],
                                ['key'=>'black', 'label'=>'Black Crystal', 'img'=>'2250'],
                            ];
                        @endphp
                        @foreach($crystals as $c)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-[#111622]/50 transition-colors group {{ !$loop->last ? 'border-b border-slate-100 dark:border-[#2a3449]' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-white dark:bg-[#111622] shadow-sm border border-slate-100 dark:border-[#2a3449] flex items-center justify-center p-1.5">
                                        <img src="{{ asset('images/carnival/shatter/'.$c['img'].'.png') }}" alt="{{ $c['label'] }}" loading="lazy" class="w-full h-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                                    </div>
                                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $c['label'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative max-w-[120px]">
                                    <input type="number" min="0" step="0.01" x-model.number="crystalPrices.{{ $c['key'] }}" class="w-full bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] text-slate-800 dark:text-slate-100 text-center font-bold px-3 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition" placeholder="0">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative inline-block w-full max-w-[160px]">
                                    <select x-model="crystalTypes.{{ $c['key'] }}" class="w-full bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] text-slate-600 dark:text-slate-300 font-medium px-3 py-2 pr-8 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer appearance-none">
                                        <option value="200/?">WL per 200 items</option>
                                        <option value="wl/item">WL per 1 item</option>
                                        <option value="per wl">Items per WL</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="md:hidden bg-white dark:bg-[#151b2b]">
                @foreach($crystals as $c)
                <div class="p-4 flex flex-col gap-3 hover:bg-slate-50/50 dark:hover:bg-[#111622]/50 transition-colors {{ !$loop->last ? 'border-b border-slate-100 dark:border-[#2a3449]' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-[#111622] shadow-sm border border-slate-100 dark:border-[#2a3449] flex items-center justify-center p-1.5 shrink-0">
                            <img src="{{ asset('images/carnival/shatter/'.$c['img'].'.png') }}" alt="{{ $c['label'] }}" loading="lazy" class="w-full h-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                        </div>
                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ $c['label'] }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative w-full">
                            <input type="number" min="0" step="0.01" x-model.number="crystalPrices.{{ $c['key'] }}" class="w-full bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] text-slate-800 dark:text-slate-100 text-center font-bold px-3 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition" placeholder="0">
                        </div>
                        <div class="relative inline-block w-full">
                            <select x-model="crystalTypes.{{ $c['key'] }}" class="w-full bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] text-slate-600 dark:text-slate-300 font-medium px-3 py-2 pr-8 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer appearance-none">
                                <option value="200/?">WL per 200 items</option>
                                <option value="wl/item">WL per 1 item</option>
                                <option value="per wl">Items per WL</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Crafting Opportunities -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-inner-light shadow-sm border border-slate-100 dark:border-[#1c2438]">
            <div class="mb-8 p-6 bg-white/60 dark:bg-slate-800/60 border-b border-slate-100 dark:border-[#1c2438] rounded-t-[28px]">
                <div class="mb-6">
                    <h3 class="text-[17px] font-bold text-slate-800 dark:text-slate-100 tracking-tight">Harmonizing Opportunities</h3>
                    <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mt-1">Select a recipe to harmonize valuable items from your crystals.</p>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                    <template x-for="(item, itemKey) in items" :key="itemKey">
                        <button @click="selectedCraftItem = selectedCraftItem === itemKey ? null : itemKey"
                             class="w-full rounded-[20px] aspect-square sm:aspect-auto sm:min-h-[110px] border-2 transition-all flex flex-col items-center justify-center p-2.5 sm:p-3 relative group outline-none"
                             :title="item.name"
                             :class="selectedCraftItem === itemKey ? 'border-brand-400 bg-brand-50 dark:bg-brand-500/20 shadow-sm' : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-[#151b2b] hover:border-slate-200 dark:hover:border-slate-600 grayscale-[0.8] opacity-70 hover:opacity-100 hover:grayscale-[0.4]'">
                            <div class="h-10 sm:h-12 w-full flex items-center justify-center mb-1.5">
                                <img :src="'/images/carnival/shatter/' + item.img + '.png'" :alt="item.name" loading="lazy"
                                     class="max-h-full max-w-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)] transition-all duration-300"
                                     :class="selectedCraftItem === itemKey ? 'scale-110 drop-shadow-md' : 'group-hover:scale-105'">
                            </div>
                            <span class="text-[10px] sm:text-[11px] font-bold leading-tight text-center w-full whitespace-normal transition-colors duration-300"
                                  :class="selectedCraftItem === itemKey ? 'text-brand-700 dark:text-brand-300' : 'text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-200'"
                                  x-text="item.name"></span>
                        </button>
                    </template>
                </div>
                <p x-show="!selectedCraftItem" class="text-sm font-medium text-slate-400 mt-6 text-center">
                    No recipe selected. Click the icons above to select one.
                </p>
            </div>

            <!-- Detail panel -->
            <template x-if="selectedCraftItem">
                <div class="p-6 bg-white/40 dark:bg-[#151b2b]/40 border-t border-slate-100 dark:border-[#1c2438] flex flex-col lg:flex-row gap-6 items-start">

                    <!-- Left: Recipe & Input -->
                    <div class="flex-1">
                        <div class="bg-white dark:bg-[#151b2b] border border-slate-100 dark:border-[#2a3449] rounded-[24px] p-6 shadow-sm relative overflow-hidden group">
                            <div class="absolute -right-10 -top-10 w-32 h-32 bg-brand-400/5 rounded-full blur-2xl group-hover:bg-brand-400/10 transition duration-500 pointer-events-none"></div>

                            <div class="flex items-center justify-between mb-5 relative z-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-slate-50 dark:bg-[#111622] border border-slate-100 dark:border-[#2a3449] rounded-2xl flex items-center justify-center p-2.5 shadow-sm">
                                        <img :src="'/images/carnival/shatter/' + items[selectedCraftItem].img + '.png'" :alt="items[selectedCraftItem].name" loading="lazy" class="w-full h-full object-contain drop-shadow-sm rounded-lg">
                                    </div>
                                    <div>
                                        <h3 class="text-[18px] font-extrabold text-slate-800 dark:text-slate-100" x-text="items[selectedCraftItem].name"></h3>
                                        <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400">Harmonizing Requirements &amp; Output</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Produces</p>
                                    <p class="text-[20px] font-extrabold text-brand-600 dark:text-brand-400 leading-none" x-text="'x' + items[selectedCraftItem].qty"></p>
                                </div>
                            </div>

                            <div class="bg-slate-50 dark:bg-[#111622] rounded-[16px] p-4 border border-slate-100 dark:border-[#2a3449] mt-2 relative z-10">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Recipe Requirements</p>
                                <div class="flex flex-wrap gap-2">
                                    <template x-if="items[selectedCraftItem].recipe.red > 0">
                                        <div class="inline-flex items-center gap-2 bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] shadow-sm rounded-lg py-1.5 px-3">
                                            <img src="{{ asset('images/carnival/shatter/2242.png') }}" loading="lazy" class="w-5 h-5 object-contain">
                                            <span class="text-[13px] font-bold text-slate-700 dark:text-slate-200" x-text="(items[selectedCraftItem].recipe.red * (numCrafts || 1)) + 'x'"></span>
                                        </div>
                                    </template>
                                    <template x-if="items[selectedCraftItem].recipe.green > 0">
                                        <div class="inline-flex items-center gap-2 bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] shadow-sm rounded-lg py-1.5 px-3">
                                            <img src="{{ asset('images/carnival/shatter/2244.png') }}" loading="lazy" class="w-5 h-5 object-contain">
                                            <span class="text-[13px] font-bold text-slate-700 dark:text-slate-200" x-text="(items[selectedCraftItem].recipe.green * (numCrafts || 1)) + 'x'"></span>
                                        </div>
                                    </template>
                                    <template x-if="items[selectedCraftItem].recipe.blue > 0">
                                        <div class="inline-flex items-center gap-2 bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] shadow-sm rounded-lg py-1.5 px-3">
                                            <img src="{{ asset('images/carnival/shatter/2246.png') }}" loading="lazy" class="w-5 h-5 object-contain">
                                            <span class="text-[13px] font-bold text-slate-700 dark:text-slate-200" x-text="(items[selectedCraftItem].recipe.blue * (numCrafts || 1)) + 'x'"></span>
                                        </div>
                                    </template>
                                    <template x-if="items[selectedCraftItem].recipe.white > 0">
                                        <div class="inline-flex items-center gap-2 bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] shadow-sm rounded-lg py-1.5 px-3">
                                            <img src="{{ asset('images/carnival/shatter/2248.png') }}" loading="lazy" class="w-5 h-5 object-contain">
                                            <span class="text-[13px] font-bold text-slate-700 dark:text-slate-200" x-text="(items[selectedCraftItem].recipe.white * (numCrafts || 1)) + 'x'"></span>
                                        </div>
                                    </template>
                                    <template x-if="items[selectedCraftItem].recipe.black > 0">
                                        <div class="inline-flex items-center gap-2 bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] shadow-sm rounded-lg py-1.5 px-3">
                                            <img src="{{ asset('images/carnival/shatter/2250.png') }}" loading="lazy" class="w-5 h-5 object-contain">
                                            <span class="text-[13px] font-bold text-slate-700 dark:text-slate-200" x-text="(items[selectedCraftItem].recipe.black * (numCrafts || 1)) + 'x'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="bg-slate-50 dark:bg-[#111622] rounded-[16px] p-4 sm:p-5 border border-slate-100 dark:border-[#2a3449] mt-3 relative z-10 flex flex-col gap-4">
                                <div class="flex justify-between items-center gap-4">
                                    <div class="flex-1">
                                        <label class="text-[12px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2">How Many Shatter Crystal?</label>
                                        <div class="flex items-center bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] rounded-[14px] px-3 py-2.5 focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all shadow-sm w-full max-w-[200px]">
                                            <input type="number" min="1" step="1" x-model.number="numCrafts" class="bg-transparent text-slate-800 dark:text-slate-100 text-[16px] font-bold outline-none flex-1 p-0 m-0 w-full" placeholder="1">
                                        </div>
                                    </div>
                                    <div class="w-12 h-12 bg-white dark:bg-[#1c2438] rounded-xl flex items-center justify-center shadow-sm border border-slate-200 dark:border-[#2a3449] shrink-0">
                                        <img :src="'/images/carnival/shatter/' + items[selectedCraftItem].img + '.png'" :alt="items[selectedCraftItem].name" loading="lazy" class="w-7 h-7 object-contain drop-shadow-sm rounded-lg">
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pt-3 border-t border-slate-200 dark:border-[#2a3449] gap-2">
                                    <span class="text-[12px] font-medium text-slate-500 dark:text-slate-400">Total Yield: <strong class="text-slate-700 dark:text-slate-200 font-bold" x-text="(numCrafts || 1) * items[selectedCraftItem].qty"></strong></span>
                                    <span class="text-[12px] font-medium text-slate-500 dark:text-slate-400">Total Cost: <strong class="text-slate-700 dark:text-slate-200 font-bold" x-text="formatWL(getItemCost(selectedCraftItem) * (numCrafts || 1)) + ' WL'"></strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Sales & Profit -->
                    <div class="lg:w-[350px] flex flex-col gap-4">

                        <!-- Sell Price -->
                        <div class="bg-white dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-[24px] p-6 shadow-sm">
                            <label class="text-[12px] font-bold text-slate-400 uppercase tracking-widest block mb-4" x-text="'Sell ' + items[selectedCraftItem].name + ' Price'"></label>
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center bg-slate-50 dark:bg-[#111622] border border-slate-200 dark:border-[#2a3449] rounded-[14px] px-3 py-2.5 focus-within:bg-white focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all shadow-sm">
                                    <input type="number" min="0" step="0.01" x-model.number="prices[selectedCraftItem]" class="bg-transparent text-slate-800 dark:text-slate-100 text-[18px] font-bold outline-none flex-1 p-0 m-0 w-full" :placeholder="items[selectedCraftItem].type === 'per wl' ? 'Item/WL' : (items[selectedCraftItem].type === '200/?' ? 'WL/200' : '0')">
                                    <span class="text-slate-400 dark:text-slate-500 font-bold text-sm ml-2 shrink-0" x-text="items[selectedCraftItem].type === 'per wl' ? 'Item' : 'WL'"></span>
                                </div>
                                <div class="relative w-full border border-slate-200 dark:border-[#2a3449] rounded-[14px] overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-brand-100 focus-within:border-brand-400 transition-all group">
                                    <select x-model="items[selectedCraftItem].type" class="w-full bg-slate-50 dark:bg-[#111622] text-slate-600 dark:text-slate-300 font-bold px-3 py-2.5 pr-8 outline-none group-hover:bg-white dark:group-hover:bg-[#151b2b] cursor-pointer appearance-none text-[14px] transition-colors">
                                        <option value="per wl">Items per WL</option>
                                        <option value="200/?">WL per 200 items</option>
                                        <option value="wl/item">WL per 1 item</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                        <svg class="h-4 w-4 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 pt-4 border-t border-slate-100 dark:border-[#2a3449]">
                                <div class="flex justify-between items-center">
                                    <span class="text-[12px] font-bold text-slate-400 uppercase tracking-widest">Total Revenue</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-200 text-[15px]" x-text="formatWL(getItemRevenue(selectedCraftItem, numCrafts || 1) + questRevenue) + ' WL'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Quest -->
                        <div class="bg-white dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-[24px] p-6 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <label class="text-[12px] font-bold text-slate-400 uppercase tracking-widest">Shatter Crystal Quest <span class="text-brand-400">(Opsional)</span></label>
                            </div>
                            <div class="flex flex-col gap-3">
                                <div>
                                    <p class="text-[11px] font-medium text-slate-400 mb-1.5">Sell price per quest (WL)</p>
                                    <div class="flex items-center bg-slate-50 dark:bg-[#111622] border border-slate-200 dark:border-[#2a3449] rounded-[14px] px-3 py-2.5 focus-within:bg-white focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all shadow-sm">
                                        <input type="number" min="0" step="0.01" x-model.number="questPrice" class="bg-transparent text-slate-800 dark:text-slate-100 text-[18px] font-bold outline-none flex-1 p-0 m-0 w-full" placeholder="0">
                                        <span class="text-slate-400 font-bold text-sm ml-2 shrink-0">WL</span>
                                    </div>
                                </div>
                                <div class="bg-slate-50 dark:bg-[#111622] rounded-2xl px-4 py-3 border border-slate-100 dark:border-[#2a3449] flex justify-between items-center">
                                    <div>
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Quests</p>
                                        <p class="text-[14px] font-extrabold text-slate-700 dark:text-slate-200" x-text="(numCrafts || 1) + ' quest'"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Quest Revenue</p>
                                        <p class="text-[14px] font-extrabold text-emerald-600 dark:text-emerald-400" x-text="formatWL(questRevenue) + ' WL'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Projected Profit -->
                        <div class="rounded-[20px] p-5 text-white shadow-md relative overflow-hidden group flex-1 flex flex-col justify-center"
                            :style="netProfit >= 0 ? 'background: linear-gradient(to bottom right, #10b981, #14b8a6);' : 'background: linear-gradient(to bottom right, #ef4444, #dc2626);'">
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/20 rounded-full blur-xl group-hover:scale-150 transition duration-700 pointer-events-none"></div>
                            <div class="relative z-10 text-left">
                                <p class="text-[11px] font-bold uppercase tracking-widest mb-1" :style="netProfit >= 0 ? 'color: #d1fae5;' : 'color: #fee2e2;'">Projected Profit</p>
                                <p class="text-[32px] font-extrabold text-white leading-none tracking-tight whitespace-nowrap" x-text="(netProfit >= 0 ? '+' : '') + formatWL(netProfit) + ' WL'"></p>
                                <div class="mt-2.5 inline-block rounded-full px-2.5 py-0.5 border border-white/80">
                                    <p class="text-[11px] font-semibold text-white tracking-wide" x-text="'Margin: ' + profitPercentage"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
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
<script src="{{ asset('js/shatter-crystal.js') }}?v=4.1.0"></script>
@endsection
