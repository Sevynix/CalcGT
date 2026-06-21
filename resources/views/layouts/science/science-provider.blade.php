@extends('layouts.app')
@section('title', "Science Station Provider Calculator")
@section('meta_description', 'Monitor Science Station chemical outputs and track your harvesting profits as a Growtopia science provider.')

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
                <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight text-slate-900 flex items-center flex-wrap gap-3">
                    <img src="{{ asset('images/science/science_station.png') }}" alt="Science Station" class="w-10 h-10 object-contain drop-shadow-sm">
                    Science Station Provider
                    <span
                        class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Calculator</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Easily calculate your expected returns and profit margins from
                    breaking down Science Stations</p>

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
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

            <!-- Summary Card -->
            <div
                class="bg-white rounded-[32px] p-8 lg:col-span-1 shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">

                <!-- Background Decoration -->
                <div
                    class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-emerald-400/10 to-teal-400/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                </div>

                <div class="relative z-10 flex flex-col h-full items-center text-center">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img src="{{ asset('images/lock/worldlock.png') }}" alt="WL" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                        <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Revenue Overview</h2>
                    </div>

                    <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-700/50 rounded-[20px] p-6 max-w-sm w-full mx-auto shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1">
                        <p class="text-[14px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2">Total Expected Revenue</p>
                        <p class="text-[36px] font-extrabold text-slate-800 leading-none" x-text="formatTotalRevenue()"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Crafting Section with Combined Toggle & Table -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-inner-light shadow-sm border border-slate-100">
            <!-- Header with Toggle Buttons -->
            <div class="mb-8 p-6 bg-white/60 dark:bg-[#151b2b]/60 border-b border-slate-100 dark:border-[#1c2438] rounded-t-[28px]">
                <div class="mb-6">
                    <h3 class="text-[17px] font-bold text-slate-800 tracking-tight">Crafting Opportunities</h3>
                    <p class="text-[13px] font-medium text-slate-500 mt-1">Select a recipe to craft valuable items from your chemicals.</p>
                </div>
                
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                    <template x-for="(recipe, recipeKey) in recipes" :key="recipeKey">
                        <button @click="selectedCraftItem = selectedCraftItem === recipeKey ? null : recipeKey" 
                             class="w-full rounded-[20px] aspect-square sm:aspect-auto sm:min-h-[110px] border-2 transition-all flex flex-col items-center justify-center p-2.5 sm:p-3 relative group outline-none"
                             :title="recipe.name"
                             :class="selectedCraftItem === recipeKey ? 'border-brand-400 bg-brand-50 dark:bg-brand-500/20 shadow-sm' : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-[#151b2b] hover:border-slate-200 dark:hover:border-[#2a3449] grayscale-[0.8] opacity-70 hover:opacity-100 hover:grayscale-[0.4]'">
                            
                            <div class="h-10 sm:h-12 w-full flex items-center justify-center mb-1.5">
                                <img :src="asset(recipe.icon)" :alt="recipe.name"
                                     class="max-h-full max-w-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)] transition-all duration-300"
                                     :class="selectedCraftItem === recipeKey ? 'scale-110 drop-shadow-md' : 'group-hover:scale-105'">
                            </div>
                            
                            <span class="text-[10px] sm:text-[11px] font-bold leading-tight text-center w-full whitespace-normal transition-colors duration-300"
                                  :class="selectedCraftItem === recipeKey ? 'text-brand-700 dark:text-brand-300' : 'text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-200'"
                                  x-text="recipe.name"></span>
                            
                            <div x-show="selectedCraftItem === recipeKey && canCraftItem(recipeKey) > 0" 
                                 class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm"
                                 x-text="canCraftItem(recipeKey)"></div>
                        </button>
                    </template>
                </div>
                <p x-show="!selectedCraftItem" class="text-sm font-medium text-slate-400 mt-6 text-center">
                    No recipes selected. Click the icons above to select one.
                </p>
            </div>

            <!-- Crafted Items Table (Only shows when one recipe is selected) -->
            <template x-if="selectedCraftItem">
                <div class="p-6 bg-white/40 dark:bg-[#151b2b]/40 border-t border-slate-100 dark:border-[#1c2438] flex flex-col lg:flex-row gap-6">
                    
                    <!-- Recipe Info -->
                    <div class="flex-1 bg-white dark:bg-[#151b2b] border border-slate-100 dark:border-[#2a3449] rounded-[24px] p-6 shadow-sm relative overflow-hidden group">
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-brand-400/5 rounded-full blur-2xl group-hover:bg-brand-400/10 transition duration-500 pointer-events-none"></div>
                        
                        <div class="flex items-center gap-4 mb-5 relative z-10">
                            <div class="w-14 h-14 bg-slate-50 dark:bg-[#111622] border border-slate-100 dark:border-[#2a3449] rounded-2xl flex items-center justify-center p-2.5 shadow-sm">
                                <img :src="asset(recipes[selectedCraftItem].icon)" :alt="recipes[selectedCraftItem].name" class="w-full h-full object-contain drop-shadow-sm">
                            </div>
                            <div>
                                <h3 class="text-[18px] font-extrabold text-slate-800" x-text="recipes[selectedCraftItem].name"></h3>
                                <p class="text-[13px] font-medium text-slate-500">Crafting Requirements & Output</p>
                            </div>
                        </div>

                        <!-- Ingredients List -->
                        <div class="bg-slate-50 dark:bg-[#111622] rounded-[16px] p-4 border border-slate-100 dark:border-[#2a3449] mt-2 relative z-10">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Recipe Requirements</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="(needed, chem) in recipes[selectedCraftItem]" :key="chem">
                                    <template x-if="needed > 0 && chem !== 'name' && chem !== 'icon' && chem !== 'yield'">
                                        <div class="inline-flex items-center gap-2 bg-white border border-slate-200 dark:border-[#2a3449] shadow-sm rounded-lg py-1.5 px-3" :title="items[chem] ? items[chem].name : (recipes[chem] ? recipes[chem].name : '')">
                                            <img :src="asset(items[chem] ? items[chem].icon : recipes[chem].icon)" class="w-5 h-5 object-contain">
                                            <span class="text-[13px] font-bold text-slate-700" x-text="needed + 'x'"></span>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>

                        <!-- Leftover Stock -->
                        <div class="bg-slate-50 dark:bg-[#111622] rounded-[16px] p-4 border border-slate-100 dark:border-[#2a3449] mt-3 relative z-10">
                            <div class="flex justify-between items-center mb-3">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Leftover Base Stock</p>
                                <span class="text-[9px] text-slate-500 font-bold tracking-wider uppercase bg-white dark:bg-[#151b2b] px-2 py-0.5 rounded-md shadow-sm border border-slate-200 dark:border-[#2a3449]">After Max Craft</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="(left, chem) in getLeftoverBaseChemicals(selectedCraftItem)" :key="chem">
                                    <template x-if="left > 0 && items[chem]">
                                        <div class="inline-flex items-center gap-2 bg-white border border-slate-200 dark:border-[#2a3449] shadow-sm rounded-lg py-1 px-2.5" :title="items[chem].name">
                                            <img :src="asset(items[chem].icon)" class="w-4 h-4 object-contain opacity-70">
                                            <span class="text-[12px] font-bold text-slate-600" x-text="left"></span>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="!Object.values(getLeftoverBaseChemicals(selectedCraftItem)).some(v => v > 0)">
                                    <span class="text-slate-400 text-[12px] font-medium italic">All chemicals consumed or 0 stock entered.</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Area -->
                    <div class="lg:w-[350px] flex flex-col gap-4">
                        <div class="flex items-center justify-between bg-white dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-[20px] p-5 shadow-sm">
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Max You Can Craft</p>
                                <p class="text-[32px] font-extrabold text-slate-800 leading-none" x-text="canCraftItem(selectedCraftItem)"></p>
                            </div>
                            <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center shadow-inner border border-emerald-100/50 dark:border-emerald-700/50 shrink-0">
                                <img :src="asset(recipes[selectedCraftItem].icon)" class="w-8 h-8 object-contain drop-shadow-md">
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-[#151b2b] border border-slate-200 dark:border-[#2a3449] rounded-[20px] p-5 shadow-sm">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3 block">Selling Estimation</label>
                            
                            <div class="flex flex-col gap-3">
                                <!-- Price Input -->
                                <div class="flex items-center bg-slate-50 dark:bg-[#111622] border border-slate-200 dark:border-[#2a3449] rounded-[14px] px-3 py-2.5 focus-within:bg-white focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all shadow-sm">
                                    <input type="number" min="0" step="0.01" x-model.number="craftingPrices[selectedCraftItem]"
                                        class="bg-transparent text-slate-800 text-[18px] font-bold outline-none flex-1 p-0 m-0 w-full"
                                        :placeholder="craftingPriceTypes[selectedCraftItem] === 'per wl' ? 'Items/WL' : (craftingPriceTypes[selectedCraftItem] === '200/?' ? 'WL/200' : '0')">
                                </div>

                                <!-- Unit Select -->
                                <div class="relative w-full border border-slate-200 dark:border-[#2a3449] rounded-[14px] overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-brand-100 focus-within:border-brand-400 transition-all group">
                                    <select x-model="craftingPriceTypes[selectedCraftItem]"
                                        class="w-full bg-slate-50 dark:bg-[#111622] text-slate-600 font-bold px-3 py-2.5 pr-8 outline-none group-hover:bg-white dark:group-hover:bg-[#151b2b] cursor-pointer appearance-none text-[14px] transition-colors">
                                        <option value="per wl">Items per WL</option>
                                        <option value="200/?">WL per 200 items</option>
                                        <option value="wl/item">WL per 1 item</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                        <svg class="h-4 w-4 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-[20px] p-5 text-white shadow-md relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/20 rounded-full blur-xl group-hover:scale-150 transition duration-700 pointer-events-none"></div>
                            <div class="relative z-10">
                                <p class="text-[11px] font-bold text-emerald-100 uppercase tracking-widest mb-1">Projected Profit</p>
                                <p class="text-[32px] font-extrabold text-white leading-none tracking-tight whitespace-nowrap" x-text="formatCraftingProfit(selectedCraftItem)"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
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
            <!-- MOBILE: Card layout (< md)       -->
            <!-- ================================ -->
            <div class="md:hidden divide-y divide-slate-100">
                <template x-for="(item, key) in items" :key="key">
                    <div class="p-4 flex flex-col gap-3">
                        <!-- Item name + revenue -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex-shrink-0 flex items-center justify-center p-1.5">
                                    <img :src="asset(item.icon)" :alt="item.name"
                                        class="w-full h-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                                </div>
                                <span class="font-bold text-slate-800 text-sm" x-text="item.name"></span>
                            </div>
                            <span class="font-bold text-emerald-600 text-base shrink-0 ml-2" x-text="getItemRevenue(key) + ' WL'"></span>
                        </div>
                        <!-- Inputs row -->
                        <div class="grid grid-cols-3 gap-2">
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Stock</label>
                                <input type="number" min="0" x-model.number="stocks[key]"
                                    class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-800 text-center font-bold px-2 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition text-sm"
                                    placeholder="0">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Price</label>
                                <input type="number" min="0" step="0.01" x-model.number="prices[key]"
                                    class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-800 text-center font-bold px-2 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition text-sm"
                                    :placeholder="item.type === 'per wl' ? 'Per WL' : (item.type === '200/?' ? 'WL/200' : 'WL/1')">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Type</label>
                                <select x-model="item.type"
                                    class="w-full bg-white border border-slate-200 dark:border-[#2a3449] text-slate-600 font-medium px-1 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer text-xs">
                                    <option value="per wl">Per WL</option>
                                    <option value="200/?">WL/200</option>
                                    <option value="wl/item">WL/Item</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- ================================ -->
            <!-- DESKTOP: Full table (>= md)      -->
            <!-- ================================ -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-semibold tracking-wide text-xs">
                            <th class="px-4 py-3 uppercase">Chemical</th>
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
                                            class="w-full min-w-[140px] bg-white border border-slate-200 dark:border-[#2a3449] text-slate-600 font-medium px-3 py-2 pr-8 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer appearance-none">
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
<script src="{{ asset('js/science-provider.js') }}"></script>
@endsection
