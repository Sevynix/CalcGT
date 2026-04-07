@extends('layouts.app')
@section('title', "Science Station Provider Calculator")

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
                    <img src="{{ asset('images/science/science_station.png') }}" alt="Science Station"
                        class="w-10 h-10 object-contain drop-shadow-sm">
                    Science Station Provider
                    <span
                        class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Calculator</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Easily calculate your expected returns and profit margins from
                    breaking down Science Stations</p>

                <div
                    class="mt-3 bg-amber-50 border border-amber-200/60 rounded-xl p-3 flex items-start gap-2.5 text-amber-700 max-w-lg shadow-sm">
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
                <button @click="resetCalculator()"
                    class="bg-white border border-slate-200 hover:border-slate-300 text-slate-700 px-4 py-2 rounded-xl font-medium transition shadow-sm text-sm flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Reset
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
                        <div
                            class="bg-[#E2FBE9] rounded-2xl w-14 h-14 flex items-center justify-center shadow-inner-light p-2">
                            <img src="{{ asset('images/lock/worldlock.webp') }}" alt="WL"
                                class="w-full h-full object-contain drop-shadow-md">
                        </div>
                        <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Revenue Overview</h2>
                    </div>

                    <div class="bg-emerald-50 border border-emerald-100 rounded-[20px] p-6 max-w-sm w-full mx-auto shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1">
                        <p class="text-[14px] font-bold text-emerald-600 uppercase tracking-widest mb-2">Total Expected Revenue</p>
                        <p class="text-[36px] font-extrabold text-slate-800 leading-none" x-text="formatTotalRevenue()"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Crafting Section with Combined Toggle & Table -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-inner-light shadow-sm border border-slate-100">
            <!-- Header with Toggle Buttons -->
            <div class="mb-8 p-6 bg-white/60 border-b border-slate-100 rounded-t-[28px]">
                <div class="mb-6">
                    <h3 class="text-[17px] font-bold text-slate-800 tracking-tight">Crafting Opportunities</h3>
                    <p class="text-[13px] font-medium text-slate-500 mt-1">Select a recipe to craft valuable items from your chemicals.</p>
                </div>
                
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                    <template x-for="(recipe, recipeKey) in recipes" :key="recipeKey">
                        <button @click="selectedCraftItem = selectedCraftItem === recipeKey ? null : recipeKey" 
                             class="w-full rounded-[20px] aspect-square sm:aspect-auto sm:min-h-[110px] border-2 transition-all flex flex-col items-center justify-center p-2.5 sm:p-3 relative group outline-none"
                             :title="recipe.name"
                             :class="selectedCraftItem === recipeKey ? 'border-brand-400 bg-brand-50 shadow-sm' : 'border-slate-100 bg-white hover:border-slate-200 grayscale-[0.8] opacity-70 hover:opacity-100 hover:grayscale-[0.4]'">
                            
                            <div class="h-10 sm:h-12 w-full flex items-center justify-center mb-1.5">
                                <img :src="asset(recipe.icon)" :alt="recipe.name"
                                     class="max-h-full max-w-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)] transition-all duration-300"
                                     :class="selectedCraftItem === recipeKey ? 'scale-110 drop-shadow-md' : 'group-hover:scale-105'">
                            </div>
                            
                            <span class="text-[10px] sm:text-[11px] font-bold leading-tight text-center w-full whitespace-normal transition-colors duration-300"
                                  :class="selectedCraftItem === recipeKey ? 'text-brand-700' : 'text-slate-500 group-hover:text-slate-700'"
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
                <div>
                    <div class="p-6 border-b border-slate-100 bg-white/40 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <span class="p-1.5 bg-amber-100 text-amber-600 rounded-lg">⚗️</span> <span x-text="`${recipes[selectedCraftItem].name} Requirements`"></span>
                        </h2>
                        <p class="text-sm text-slate-500">(If all chemicals are used)</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 font-semibold tracking-wide text-xs">
                                    <th class="px-6 py-4 uppercase">Chemical</th>
                                    <th class="px-6 py-4 uppercase">Needed Per Item</th>
                                    <th class="px-6 py-4 uppercase">Total Needed</th>
                                    <th class="px-6 py-4 uppercase">Current Stock</th>
                                    <th class="px-6 py-4 uppercase text-right">Can Make</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(item, key) in items" :key="key">
                                    <template x-if="recipes[selectedCraftItem][key] > 0">
                                        <tr class="hover:bg-slate-50/50 transition-colors group">
                                            <!-- Chemical Name & Icon -->
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center p-1.5">
                                                        <img :src="asset(item.icon)" :alt="item.name" class="w-full h-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                                                    </div>
                                                    <span class="font-bold text-slate-800" x-text="item.name"></span>
                                                </div>
                                            </td>

                                            <!-- Per Item -->
                                            <td class="px-6 py-4">
                                                <span class="font-bold text-slate-800" x-text="recipes[selectedCraftItem][key]"></span>
                                            </td>

                                            <!-- Total Needed -->
                                            <td class="px-6 py-4">
                                                <span class="font-bold text-slate-800" x-text="recipes[selectedCraftItem][key] * canCraftItem(selectedCraftItem)"></span>
                                            </td>

                                            <!-- Current Stock -->
                                            <td class="px-6 py-4">
                                                <span class="font-bold text-slate-800" x-text="stocks[key] || 0"></span>
                                            </td>

                                            <!-- Can Make -->
                                            <td class="px-6 py-4 text-right">
                                                <span class="font-bold text-[15px] text-emerald-600 block pt-1" x-text="Math.floor((stocks[key] || 0) / recipes[selectedCraftItem][key])"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                
                                <!-- Mysterious Chemical if needed -->
                                <template x-if="recipes[selectedCraftItem].chemm > 0">
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center p-1.5">
                                                    <img src="{{ asset('images/science/chem/m.png') }}" alt="Mysterious Chemical" class="w-full h-full object-contain drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
                                                </div>
                                                <span class="font-bold text-slate-800">Mysterious Chemical</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-800" x-text="recipes[selectedCraftItem].chemm"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-800" x-text="recipes[selectedCraftItem].chemm * canCraftItem(selectedCraftItem)"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-800" x-text="canCraftChemM()"></span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="font-bold text-[15px] text-emerald-600 block pt-1" x-text="Math.floor(canCraftChemM() / recipes[selectedCraftItem].chemm)"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Footer -->
                    <div class="p-6 border-t border-slate-100 bg-white/40">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-slate-50 border border-slate-100 rounded-lg p-4 text-center">
                                <p class="text-xs text-slate-500 uppercase font-semibold mb-2">Items You Can Make</p>
                                <p class="text-3xl font-extrabold text-brand-600" x-text="canCraftItem(selectedCraftItem)"></p>
                            </div>
                            
                            <div>
                                <label class="text-slate-600 font-bold text-xs mb-2 block uppercase">Selling Price Per Item</label>
                                <div class="flex items-center bg-white border border-slate-200 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-brand-400 focus-within:border-brand-400">
                                    <input type="number" min="0" step="0.01" x-model.number="craftingPrices[selectedCraftItem]"
                                        class="bg-transparent text-slate-800 font-bold outline-none flex-1 p-0 m-0 text-sm"
                                        placeholder="0">
                                    <span class="text-slate-500 font-bold text-sm ml-2">WL</span>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 text-center">
                                <p class="text-xs text-blue-700 font-semibold uppercase tracking-wide mb-2">Total Profit</p>
                                <p class="text-2xl font-extrabold text-blue-600" x-text="formatCraftingProfit(selectedCraftItem)"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- The Table Area -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-inner-light shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100 bg-white/40 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <span class="p-1.5 bg-red-100 text-red-500 rounded-lg">🏷️</span> Resale Items
                </h2>
                <p class="text-sm text-slate-500">Manually input your stock and set prices to calculate margins.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-semibold tracking-wide text-xs">
                            <th class="px-6 py-4 uppercase">Chemical</th>
                            <th class="px-6 py-4 uppercase">Stock</th>
                            <th class="px-6 py-4 uppercase">Resale Price</th>
                            <th class="px-6 py-4 uppercase">Sales Type</th>
                            <th class="px-6 py-4 uppercase text-right">Projected Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(item, key) in items" :key="key">
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <!-- Tool Name & Icon -->
                                <td class="px-6 py-4">
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
                                <td class="px-6 py-4">
                                    <div class="relative max-w-[100px]">
                                        <input type="number" min="0" x-model.number="stocks[key]"
                                            class="w-full bg-white border border-slate-200 text-slate-800 text-center font-bold px-3 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"
                                            placeholder="0">
                                    </div>
                                </td>

                                <!-- Price Input -->
                                <td class="px-6 py-4">
                                    <div class="relative max-w-[120px]">
                                        <input type="number" min="0" step="0.01" x-model.number="prices[key]"
                                            class="w-full bg-white border border-slate-200 text-slate-800 text-center font-bold px-3 py-2 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"
                                            :placeholder="item.type === 'per wl' ? 'Items/WL' : (item.type === '200/?' ? 'WL/200' : 'WL/Item')">
                                    </div>
                                </td>

                                <!-- Select Type -->
                                <td class="px-6 py-4">
                                    <div class="relative inline-block w-full">
                                        <select x-model="item.type"
                                            class="w-full bg-white border border-slate-200 text-slate-600 font-medium px-3 py-2 pr-8 rounded-lg outline-none focus:ring-2 focus:ring-brand-400 transition cursor-pointer appearance-none">
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
                                <td class="px-6 py-4 text-right">
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
