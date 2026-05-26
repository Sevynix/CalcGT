@extends('layouts.app')
@section('title', 'Autoclave Calculator')
@section('meta_description', 'Calculate Autoclave usage, sterilization costs, and surgery supply requirements for Growtopia\'s MSurg profession.')

@section('content')
<div x-data="autoclaveSim()" @keydown.window.enter.prevent="
    let inputs = Array.from(document.querySelectorAll('input:not([disabled]), select:not([disabled])')).filter(i => i.offsetParent !== null);
    let idx = inputs.indexOf(document.activeElement);
    if(idx > -1 && idx < inputs.length - 1) {
        inputs[idx + 1].focus();
        if(inputs[idx + 1].select) inputs[idx + 1].select();
    }
">
    <div class="max-w-5xl mx-auto space-y-8 pb-10">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <a href="/" class="inline-flex items-center text-slate-500 hover:text-brand-600 transition font-medium mb-3 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Dashboard
                </a>
                <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight text-slate-900 flex items-center flex-wrap gap-3">
                    <img src="{{ asset('images/surgery/autoclave.png') }}" alt="Autoclave" class="w-10 h-10 object-contain drop-shadow-sm">
                    Autoclave
                    <span class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Calculator</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-xl">
                    Convert 20 of one tool into 1 of each of the other 12 tools, and repeat until no further conversions can be made
                </p>

                <div class="mt-3 bg-amber-50 border border-amber-200/60 rounded-xl p-3 flex items-start gap-2.5 text-amber-700 max-w-lg shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-[13px] leading-relaxed font-medium">
                        <strong class="font-bold">Disclaimer:</strong> These projections assume 100% sell-through. Some items might take longer to sell or may not sell at your target price, so use these estimates wisely!
                    </p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex gap-3">
                                <button @click="$store.modal.show('This will clear all your inputs and saved data.', () => resetData())" class="bg-red-500 hover:bg-red-600 active:bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-md text-sm flex items-center gap-2 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset All
                </button>
            </div>
        </div>

        <!-- Top Dashboard Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Simulation Stats Card -->
            <div class="glass-card rounded-[32px] p-8 lg:col-span-1 shadow-sm border border-slate-100">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                        <img src="{{ asset('images/surgery/autoclave.png') }}" alt="Autoclave" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Autoclave Cycles</h2>
                </div>

                <div class="space-y-6">
                    <div class="flex flex-col">
                        <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Cycles Triggered</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-[32px] font-extrabold text-slate-800 leading-none tracking-tight" x-text="totalCyclesRun"></span>
                            <span class="text-[14px] font-bold text-slate-400 tracking-widest uppercase">Cycles</span>
                        </div>
                    </div>
                    <div class="text-sm text-slate-500 leading-relaxed font-medium">
                        The Autoclave actively loops until all fuel items fall below 20 pieces.
                    </div>
                </div>
            </div>

            <!-- Profit Overview Card -->
            <div class="bg-white rounded-[32px] p-8 lg:col-span-2 shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-emerald-400/10 to-teal-400/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none"></div>

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
                        <!-- Cost -->
                        <div class="bg-slate-50 border border-slate-100 rounded-[20px] p-5 flex flex-col justify-center">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Cost</p>
                            <p class="text-[24px] font-bold text-slate-800" x-text="formatWL(totalCost)"></p>
                        </div>

                        <!-- Revenue -->
                        <div class="bg-slate-50 border border-slate-100 rounded-[20px] p-5 flex flex-col justify-center">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Revenue</p>
                            <p class="text-[24px] font-bold text-slate-800" x-text="formatWL(totalRevenue)"></p>
                        </div>

                        <!-- Net Profit -->
                        <div class="rounded-[20px] p-5 text-white shadow-md flex flex-col justify-center transform transition hover:-translate-y-1"
                            :class="netProfit > 0 ? 'bg-emerald-500' : (netProfit < 0 ? 'bg-red-500' : 'bg-slate-800')">
                            <p class="text-[12px] font-bold text-white/70 uppercase tracking-widest mb-1.5">Net Profit</p>
                            <p class="text-[28px] font-extrabold leading-none" x-text="formatWL(netProfit)"></p>
                        </div>

                        <!-- Margin -->
                        <div class="bg-white border border-slate-200 rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-center">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Margin</p>
                            <p class="text-[28px] font-extrabold leading-none"
                                :class="netProfit > 0 ? 'text-emerald-500' : (netProfit < 0 ? 'text-red-500' : 'text-black')"
                                x-text="profitPercentage"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- The 2 Configuration Layers (Fuel -> Target) -->
        
        <!-- FUEL BOX -->
        <div class="glass-card bg-white rounded-[24px] p-6 shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-slate-800 mb-1 flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                    <img src="{{ asset('images/surgery/autoclave.png') }}" alt="Autoclave" class="w-8 h-8 object-contain drop-shadow-sm">
                </div> 
                1. Select Fuel Tools
            </h2>
            <p class="text-sm text-slate-500 mb-6">Click the tools below to mark them as Autoclave Fuel. Selected tools will show inputs for Quantity & Price.</p>

            <!-- Dynamic Selected Fuel Toggles -->
            <div class="grid grid-cols-5 sm:grid-cols-7 md:grid-cols-9 lg:grid-cols-13 gap-2.5 mb-6 border-b border-slate-100 pb-6">
                <template x-for="(tool, index) in tools" :key="'toggle_fuel_'+index">
                    <button @click="if(!tool.isTarget) tool.isFuel = !tool.isFuel" 
                        class="rounded-[14px] p-2 w-full aspect-square flex flex-col items-center justify-center transition-all border shadow-sm transform relative"
                        :class="tool.isTarget ? 'opacity-30 grayscale cursor-not-allowed' : (tool.isFuel ? 'ring-2 ring-brand-400 border-brand-400 bg-brand-50 hover:scale-105' : 'bg-white border-slate-200 hover:border-slate-300 opacity-60 grayscale hover:grayscale-0 hover:opacity-100 hover:scale-105')"
                        :disabled="tool.isTarget"
                        :title="tool.isTarget ? 'Selected as Target' : tool.name">
                        <img :src="getAssetPath(tool.name)" onerror="this.src='/images/roles/surgeon.png'" class="w-8 h-8 object-contain drop-shadow-sm">
                    </button>
                </template>
            </div>

            <!-- Render Input Form for FUEL TOOLS ONLY -->
            <div class="space-y-4" x-show="tools.some(t => t.isFuel)" x-transition>
                <template x-for="(tool, index) in tools" :key="'form_fuel_'+index">
                    <div x-show="tool.isFuel" x-transition.opacity
                        class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-400 rounded-l-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="flex items-center gap-3 w-40 shrink-0 select-none">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center p-1.5 shrink-0">
                                <img :src="getAssetPath(tool.name)" onerror="this.src='/images/roles/surgeon.png'" class="w-full h-full object-contain">
                            </div>
                            <span class="font-bold text-slate-700 text-sm leading-tight" x-text="tool.name"></span>
                        </div>

                        <div class="flex-1 flex flex-wrap gap-4 items-center">
                            <div class="flex flex-col">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Bought Quantity</label>
                                <input type="number" min="0" x-model.number="tool.stock" class="w-28 bg-white border border-slate-200 text-slate-800 text-center font-bold px-3 py-2 rounded-xl outline-none focus:ring-2 focus:ring-brand-400 transition shadow-sm" placeholder="0">
                            </div>
                            
                            <div class="flex flex-col">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Buy Price</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="number" min="0" step="0.01" x-model.number="tool.buyPrice" class="w-24 bg-white border border-slate-200 text-slate-600 text-center font-bold px-3 py-2 rounded-xl outline-none focus:ring-2 focus:ring-brand-400 transition shadow-sm" placeholder="0">
                                    <select x-model="tool.buyType" class="bg-white border text-center border-slate-200 text-slate-600 font-bold px-2 py-2 rounded-xl outline-none focus:ring-2 focus:ring-brand-400 cursor-pointer shadow-sm text-sm">
                                        <option value="per wl">Items / WL</option>
                                        <option value="200/?">WL / 200</option>
                                        <option value="wl/item">WL / Item</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div x-show="!tools.some(t => t.isFuel)" class="text-center text-sm text-slate-400 font-medium py-4">
                No fuel tools selected. Click the tool icons above to map them.
            </div>
        </div>

        <!-- TARGET BOX -->
        <div class="glass-card bg-white rounded-[24px] p-6 shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-slate-800 mb-1 flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200">
                    <img src="{{ asset('images/items/forsale.png') }}" alt="For sale Sign" class="w-8 h-8 object-contain drop-shadow-sm">
                </div> 
                2. Select Target Tools
            </h2>
            <p class="text-sm text-slate-500 mb-4">Click the rare tools you want to sell. They will evaluate their compound yield directly from the Fuel tools above.</p>

            <!-- Warning for Locked Inputs -->
            <div x-show="tools.some(t => t.isTarget) && totalCyclesRun === 0" x-transition.opacity
                class="mb-6 bg-amber-50 border border-amber-200/60 rounded-xl p-3 flex items-start gap-2.5 text-amber-700 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="text-[13px] leading-relaxed font-medium">
                    <strong class="font-bold">Inputs Locked!</strong> You must enter enough Fuel Quantity (20+) in Step 1 to generate yield before you can set your Target Sell Prices.
                </div>
            </div>

            <!-- Dynamic Selected Target Toggles -->
            <div class="grid grid-cols-5 sm:grid-cols-7 md:grid-cols-9 lg:grid-cols-13 gap-2.5 mb-6 border-b border-slate-100 pb-6">
                <template x-for="(tool, index) in tools" :key="'toggle_target_'+index">
                    <button @click="if(!tool.isFuel) tool.isTarget = !tool.isTarget" 
                        class="rounded-[14px] p-2 w-full aspect-square flex flex-col items-center justify-center transition-all border shadow-sm transform relative"
                        :class="tool.isFuel ? 'opacity-30 grayscale cursor-not-allowed' : (tool.isTarget ? 'ring-2 ring-emerald-400 border-emerald-400 bg-emerald-50 hover:scale-105' : 'bg-white border-slate-200 hover:border-slate-300 opacity-60 grayscale hover:grayscale-0 hover:opacity-100 hover:scale-105')"
                        :disabled="tool.isFuel"
                        :title="tool.isFuel ? 'Selected as Fuel' : tool.name">
                        <img :src="getAssetPath(tool.name)" onerror="this.src='/images/roles/surgeon.png'" class="w-8 h-8 object-contain">
                        <!-- Little Yield Badge Preview inside icon -->
                        <span x-show="getFinalCount(index) > 0" class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[11px] font-bold px-2 py-[2px] rounded-full shadow-md z-10" x-transition.scale x-text="getFinalCount(index)"></span>
                    </button>
                </template>
            </div>

            <!-- Render Input Form for TARGET TOOLS ONLY -->
            <div class="space-y-4" x-show="tools.some(t => t.isTarget)" x-transition>
                <template x-for="(tool, index) in tools" :key="'form_target_'+index">
                    <div x-show="tool.isTarget" x-transition.opacity
                        class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-slate-50/50 p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-400 rounded-l-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="flex items-center gap-3 w-40 shrink-0 select-none">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center p-1.5 shrink-0">
                                <img :src="getAssetPath(tool.name)" onerror="this.src='/images/roles/surgeon.png'" class="w-full h-full object-contain mb-0">
                            </div>
                            <span class="font-bold text-slate-700 text-sm leading-tight" x-text="tool.name"></span>
                        </div>

                        <div class="flex-1 flex flex-wrap gap-6 items-center">
                            <div class="flex flex-col">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Machine Yield</label>
                                <span class="inline-flex items-center justify-center bg-white border border-slate-200 text-emerald-600 font-extrabold px-3 py-2 rounded-xl shadow-sm w-24 h-10"
                                    :class="getFinalCount(index) > 0 ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'opacity-70'">
                                    <span x-text="getFinalCount(index)"></span>
                                </span>
                            </div>
                            
                            <div class="flex flex-col">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Desired Sell Price</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="number" min="0" step="0.01" x-model.number="tool.sellPrice" class="w-24 bg-white border border-slate-200 text-slate-800 text-center font-bold px-3 py-2 rounded-xl outline-none focus:ring-2 focus:ring-emerald-400 transition shadow-sm" placeholder="0" :disabled="getFinalCount(index) === 0" :class="getFinalCount(index) === 0 ? 'opacity-50' : ''">
                                    <select x-model="tool.sellType" class="bg-white border text-center border-slate-200 text-slate-600 font-bold px-2 py-2 rounded-xl outline-none focus:ring-2 focus:ring-emerald-400 cursor-pointer shadow-sm text-sm" :disabled="getFinalCount(index) === 0" :class="getFinalCount(index) === 0 ? 'opacity-50' : ''">
                                        <option value="per wl">Items / WL</option>
                                        <option value="200/?">WL / 200</option>
                                        <option value="wl/item">WL / Item</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div x-show="!tools.some(t => t.isTarget)" class="text-center text-sm text-slate-400 font-medium py-4">
                No target tools selected. Click the tool icons above to calculate revenue on them.
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/autoclave-tools.js') }}"></script>
@endsection
