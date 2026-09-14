@extends('layouts.app')
@section('title', 'Fish Training Calculator')
@section('meta_description', 'Calculate your profit from training perfect fish and crabs to max level in Growtopia. Set perfect fish price, food type, and sell price to see your margin.')

@section('content')
<div x-data="fishTraining()" @keydown.window.enter.prevent="
    let inputs = Array.from(document.querySelectorAll('input'));
    let idx = inputs.indexOf(document.activeElement);
    if(idx > -1 && idx < inputs.length - 1) {
        inputs[idx + 1].focus();
        if(inputs[idx + 1].select) inputs[idx + 1].select();
    }
">
    <div class="max-w-5xl mx-auto space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <a href="/"
                    class="inline-flex items-center text-slate-500 hover:text-brand-600 transition font-medium mb-3 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Dashboard
                </a>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 flex flex-wrap items-center gap-2 sm:gap-3">
                    <img src="{{ asset('images/fishTraining/trainingPort.png') }}" alt="Fish Training" loading="lazy"
                        class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 object-contain drop-shadow-sm shrink-0">
                    <span class="break-words leading-tight">Fish Training</span>
                    <span class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-[10px] sm:text-xs font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full uppercase tracking-wider shadow-sm shrink-0 whitespace-nowrap">Calculator</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Calculate your profit from training perfect fish and crabs to max level. Select the creature, set prices, and see your margin.</p>

                <div class="mt-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-200/60 dark:border-amber-700/50 rounded-xl p-3 flex items-start gap-2.5 text-amber-700 dark:text-amber-400 max-w-lg shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <p class="text-[13px] leading-relaxed font-medium">
                        <strong class="font-bold">Note:</strong> Each training assumes leveling one fish from Level 1 to Max (5,325 XP total). Food amounts shown are for full max training.
                    </p>
                </div>

                <div class="mt-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200/60 dark:border-blue-700/50 rounded-xl p-3 flex items-start gap-2.5 text-blue-700 dark:text-blue-400 max-w-lg shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-[13px] leading-relaxed font-medium">
                        <strong class="font-bold">How it works:</strong> This calculator focuses on selling max-trained fish to other players, mainly during the Carnival event (Training fishes Quest). Train a perfect fish to Level 10 (max), then sell the fully trained fish for profit
                    </p>
                </div>
            </div>

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

        {{-- Fish / Crab Selector --}}
        <div class="glass-card rounded-3xl overflow-hidden shadow-inner-light shadow-sm border border-slate-100 dark:border-[#1c2438]">
            <div class="p-6 border-b border-slate-100 dark:border-[#1c2438] bg-white/40 dark:bg-[#151b2b]/40 flex flex-wrap justify-between items-center gap-3">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-[#111622] flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img src="{{ asset('images/fishTraining/trainingPort.png') }}" alt="Fish Tank Port" loading="lazy" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Select Fish / Crab to Train</h2>
                        <p class="text-[13px] text-slate-500 dark:text-slate-400 font-medium">Choose the perfect fish or crab you want to train to max level.</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white dark:bg-[#151b2b] space-y-5">

                {{-- Fish --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">Fish</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="[key, creature] in fishList" :key="key">
                            <button
                                @click="selectedCreature = selectedCreature === key ? null : key; selectedFoodIdx = null"
                                :title="creature.name"
                                :class="selectedCreature === key
                                    ? 'border-brand-400 bg-brand-50 dark:bg-brand-500/20 shadow-sm'
                                    : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-[#1c2438] hover:border-slate-200 dark:hover:border-[#2a3449] opacity-70 hover:opacity-100'"
                                class="relative rounded-[20px] border-2 transition-all flex items-center justify-center w-14 h-14 group outline-none cursor-pointer">
                                <img :src="'/images/fishTraining/' + creature.id + '.png'" :alt="creature.name"
                                    loading="lazy"
                                    class="w-11 h-11 object-contain drop-shadow-sm transition-all duration-300"
                                    :class="selectedCreature === key ? 'scale-110 drop-shadow-md' : 'group-hover:scale-105'">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Crabs --}}
                <div>
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">Crabs</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="[key, creature] in crabList" :key="key">
                            <button
                                @click="selectedCreature = selectedCreature === key ? null : key; selectedFoodIdx = null"
                                :title="creature.name"
                                :class="selectedCreature === key
                                    ? 'border-brand-400 bg-brand-50 dark:bg-brand-500/20 shadow-sm'
                                    : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-[#1c2438] hover:border-slate-200 dark:hover:border-[#2a3449] opacity-70 hover:opacity-100'"
                                class="relative rounded-[20px] border-2 transition-all flex items-center justify-center w-14 h-14 group outline-none cursor-pointer">
                                <img :src="'/images/fishTraining/' + creature.id + '.png'" :alt="creature.name"
                                    loading="lazy"
                                    class="w-11 h-11 object-contain drop-shadow-sm transition-all duration-300"
                                    :class="selectedCreature === key ? 'scale-110 drop-shadow-md' : 'group-hover:scale-105'">
                            </button>
                        </template>
                    </div>
                </div>

                <p x-show="!selectedCreature" class="text-sm font-medium text-slate-400 text-center py-1">
                    Click an icon above to select the fish or crab you want to train.
                </p>

                {{-- Selected badge --}}
                <div x-show="selectedCreature" class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-[#111622] rounded-2xl border border-slate-100 dark:border-[#2a3449]">
                    <img :src="'/images/fishTraining/' + (selectedData?.id ?? '') + '.png'" :alt="selectedData?.name ?? ''"
                        loading="lazy" class="w-10 h-10 object-contain drop-shadow-sm shrink-0">
                    <div>
                        <p class="text-[13px] font-bold text-slate-800 dark:text-slate-100" x-text="selectedData?.name ?? ''"></p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium" x-text="selectedData?.type === 'crab' ? 'Crab' : 'Fish'"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Input + Profit (shown after selecting a creature) --}}
        <template x-if="selectedCreature">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left: Inputs --}}
                <div class="glass-card rounded-[32px] p-8 lg:col-span-1 shadow-sm border border-slate-100 dark:border-[#1c2438] flex flex-col h-full">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-[#111622] flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                            <img :src="'/images/fishTraining/' + (selectedData?.id ?? '') + '.png'" :alt="selectedData?.name ?? ''" class="w-8 h-8 object-contain drop-shadow-sm">
                        </div>
                        <h2 class="text-[22px] font-extrabold text-slate-800 dark:text-slate-100 tracking-tight" x-text="selectedData?.name ?? 'Details'"></h2>
                    </div>

                    <div class="space-y-5 flex-1">

                        {{-- Perfect Fish Price --}}
                        <div class="flex flex-col">
                            <label class="text-slate-600 dark:text-slate-400 font-bold text-[15px] mb-2">Perfect Fish Price</label>
                            <div class="flex items-center bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full mb-2">
                                <input type="number" id="pfishPriceInput" min="0" step="0.01" x-model.number="pfishPrice"
                                    class="bg-transparent text-slate-800 dark:text-slate-100 text-[16px] font-medium outline-none w-full p-0 m-0"
                                    placeholder="0">
                                <span class="text-slate-500 font-bold text-[14px] ml-2 shrink-0" x-text="pfishPriceType === 'wl/fish' ? 'WL' : 'Fish/WL'"></span>
                            </div>
                            <div class="relative w-full border border-slate-200 dark:border-[#2a3449] rounded-xl overflow-hidden shadow-sm">
                                <select id="pfishPriceType" x-model="pfishPriceType"
                                    class="w-full bg-slate-50 dark:bg-[#111622] text-slate-600 dark:text-slate-300 font-medium px-3 py-2.5 pr-8 outline-none cursor-pointer appearance-none text-[13px]">
                                    <option value="wl/fish">WL per Perfect Fish</option>
                                    <option value="fish/wl">Perfect Fish per WL</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Number of Trainings --}}
                        <div class="flex flex-col">
                            <label class="text-slate-600 dark:text-slate-400 font-bold text-[15px] mb-2">Number of Trainings</label>
                            <div class="flex items-center bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                                <input type="number" id="numTrainingsInput" min="1" step="1" x-model.number="numTrainings"
                                    class="bg-transparent text-slate-800 dark:text-slate-100 text-[16px] font-medium outline-none w-full p-0 m-0"
                                    placeholder="1">
                                <span class="text-slate-500 font-bold text-[14px] ml-2 shrink-0">Fish</span>
                            </div>
                        </div>

                        {{-- Food Selector --}}
                        <div class="flex flex-col">
                            <label class="text-slate-600 dark:text-slate-400 font-bold text-[15px] mb-2">Training Food</label>
                            <div class="space-y-1.5">
                                <template x-for="(food, idx) in (selectedData?.food ?? [])" :key="idx">
                                    <button
                                        @click="selectedFoodIdx = selectedFoodIdx === idx ? null : idx"
                                        :class="selectedFoodIdx === idx
                                            ? 'border-brand-400 bg-brand-50 dark:bg-brand-500/20 shadow-sm'
                                            : 'border-slate-200 dark:border-[#2a3449] bg-white dark:bg-[#1c2438] hover:border-slate-300 dark:hover:border-slate-500'"
                                        class="w-full rounded-xl border-2 transition-all p-2.5 text-left outline-none flex items-center gap-3 cursor-pointer">
                                        <img :src="getFoodImage(food)" :alt="food.name" loading="lazy"
                                            class="w-10 h-10 object-contain drop-shadow-sm shrink-0 transition-all duration-300"
                                            :class="selectedFoodIdx === idx ? 'scale-110' : 'group-hover:scale-105'"
                                            x-show="getFoodImage(food)">
                                        <span class="font-bold text-slate-800 dark:text-slate-100 text-[13px] flex-1" x-text="food.name"></span>
                                        <span class="text-[12px] font-bold shrink-0"
                                            :class="selectedFoodIdx === idx ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500'"
                                            x-text="food.xpPerLb ? food.xpPerLb + ' XP/lb' : (food.xpPerItem ? food.xpPerItem + ' XP/item' : '')">
                                        </span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Food Price (shown after food selected) --}}
                        <template x-if="selectedFoodIdx !== null">
                            <div class="flex flex-col">
                                <label class="text-slate-600 dark:text-slate-400 font-bold text-[15px] mb-2">
                                    <span x-text="selectedFood?.name ?? 'Food'"></span> Price
                                </label>
                                <div class="flex items-center bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full"
                                     :class="{'mb-2': selectedFood?.xpPerItem}">
                                    <input type="number" id="foodLbPerWlInput" min="0" step="0.01" x-model.number="foodLbPerWl"
                                        class="bg-transparent text-slate-800 dark:text-slate-100 text-[16px] font-medium outline-none w-full p-0 m-0"
                                        placeholder="0">
                                    <span class="text-slate-500 font-bold text-[14px] ml-2 shrink-0"
                                        x-text="!selectedFood?.xpPerItem ? 'LB / WL' : (foodPriceType === 'item/wl' ? 'Items / WL' : 'WL / Item')"></span>
                                </div>
                                <template x-if="selectedFood?.xpPerItem">
                                    <div class="relative w-full border border-slate-200 dark:border-[#2a3449] rounded-xl overflow-hidden shadow-sm">
                                        <select id="foodPriceType" x-model="foodPriceType"
                                            class="w-full bg-slate-50 dark:bg-[#111622] text-slate-600 dark:text-slate-300 font-medium px-3 py-2.5 pr-8 outline-none cursor-pointer appearance-none text-[13px]">
                                            <option value="item/wl">Items per WL</option>
                                            <option value="wl/item">WL per Item</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Fish Training Quest Price --}}
                        <div class="flex flex-col">
                            <label class="text-slate-600 dark:text-slate-400 font-bold text-[15px] mb-1">Fish Training Quest Price</label>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium mb-2">Price per maxed fish sold to buyers during Carnival.</p>
                            <div class="flex items-center bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                                <input type="number" id="sellPriceInput" min="0" step="0.01" x-model.number="sellPrice"
                                    class="bg-transparent text-slate-800 dark:text-slate-100 text-[16px] font-medium outline-none w-full p-0 m-0"
                                    placeholder="0">
                                <span class="text-slate-500 font-bold text-[14px] ml-2 shrink-0">WL</span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Right: Profit Overview --}}
                <div class="bg-white dark:bg-[#151b2b] rounded-[32px] p-8 lg:col-span-2 shadow-sm border border-slate-100 dark:border-[#1c2438] flex flex-col justify-between relative overflow-hidden group">

                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-emerald-400/10 to-teal-400/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-[#111622] flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                                <img loading="lazy" src="{{ asset('images/lock/worldlock.png') }}" alt="WL" class="w-8 h-8 object-contain drop-shadow-sm">
                            </div>
                            <h2 class="text-[22px] font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Profit Overview</h2>
                        </div>

                        {{-- XP + LB Info row --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                            <div class="bg-slate-50 dark:bg-[#111622] border border-slate-100 dark:border-[#2a3449] rounded-[16px] p-4 flex flex-col justify-center">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">XP / Training</p>
                                <p class="text-[18px] font-bold text-slate-800 dark:text-slate-100" x-text="fmt(xpPerTraining)"></p>
                            </div>
                            <div class="bg-slate-50 dark:bg-[#111622] border border-slate-100 dark:border-[#2a3449] rounded-[16px] p-4 flex flex-col justify-center">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total XP</p>
                                <p class="text-[18px] font-bold text-slate-800 dark:text-slate-100" x-text="fmt(totalXP)"></p>
                            </div>
                            <div class="bg-slate-50 dark:bg-[#111622] border border-slate-100 dark:border-[#2a3449] rounded-[16px] p-4 flex flex-col justify-center">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1" x-text="selectedFood?.xpPerItem ? 'Items / Training' : 'LB / Training'"></p>
                                <p class="text-[18px] font-bold text-slate-800 dark:text-slate-100" x-text="fmtLb(lbPerTraining)"></p>
                            </div>
                            <div class="bg-slate-50 dark:bg-[#111622] border border-slate-100 dark:border-[#2a3449] rounded-[16px] p-4 flex flex-col justify-center">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1" x-text="selectedFood?.xpPerItem ? 'Total Items' : 'Total LB'"></p>
                                <p class="text-[18px] font-bold text-slate-800 dark:text-slate-100" x-text="fmtLb(totalLb)"></p>
                            </div>
                        </div>

                        {{-- Main profit grid --}}
                        <div class="flex-grow grid grid-cols-2 gap-4">

                            {{-- Total Cost --}}
                            <div class="bg-slate-50 dark:bg-[#111622] border border-slate-100 dark:border-[#2a3449] rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 flex flex-col justify-center overflow-hidden">
                                <p class="text-[11px] sm:text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Cost</p>
                                <p class="text-[18px] sm:text-[24px] font-bold text-slate-800 dark:text-slate-100 break-words" x-text="fmtWl(totalCost) + ' WL'"></p>
                                <p class="text-[11px] text-slate-400 font-medium mt-1">
                                    Perfect fish: <span class="font-bold text-slate-600 dark:text-slate-300" x-text="fmtWl(pfishBuyCostTotal) + ' WL'"></span>
                                    + Food: <span class="font-bold text-slate-600 dark:text-slate-300" x-text="fmtWl(foodCostTotal) + ' WL'"></span>
                                </p>
                            </div>

                            {{-- Total Revenue --}}
                            <div class="bg-slate-50 dark:bg-[#111622] border border-slate-100 dark:border-[#2a3449] rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 flex flex-col justify-center overflow-hidden">
                                <p class="text-[11px] sm:text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Revenue</p>
                                <p class="text-[18px] sm:text-[24px] font-bold text-slate-800 dark:text-slate-100 break-words" x-text="fmtWl(income) + ' WL'"></p>
                                <p class="text-[11px] text-slate-400 font-medium mt-1">
                                    <span x-text="numTrainings || 1"></span> trained <span x-text="selectedData?.type === 'crab' ? 'crab(s)' : 'fish'"></span> sold
                                </p>
                            </div>

                            {{-- Net Profit --}}
                            <div class="rounded-[20px] p-5 text-white shadow-md flex flex-col justify-center transform transition hover:-translate-y-1"
                                :class="netProfit > 0 ? 'bg-emerald-500' : (netProfit < 0 ? 'bg-red-500' : 'bg-slate-800')">
                                <p class="text-[12px] font-bold text-white/80 uppercase tracking-widest mb-1">Projected Profit</p>
                                <p class="text-[28px] font-extrabold leading-none" x-text="(netProfit >= 0 ? '+' : '') + fmtWl(netProfit) + ' WL'"></p>
                            </div>

                            {{-- Margin --}}
                            <div class="bg-white dark:bg-[#1c2438] border border-slate-200 dark:border-[#2a3449] rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-center overflow-hidden">
                                <p class="text-[11px] sm:text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Margin</p>
                                <p class="text-[20px] sm:text-[28px] font-extrabold leading-tight sm:leading-none break-words"
                                    :class="netProfit > 0 ? 'text-emerald-500' : (netProfit < 0 ? 'text-red-500' : 'text-slate-800 dark:text-slate-200')"
                                    x-text="profitMargin"></p>
                                <p class="text-[11px] text-slate-400 font-medium mt-1" x-text="'Per fish: ' + (netProfit >= 0 ? '+' : '') + fmtWl(netProfit / (numTrainings || 1)) + ' WL'"></p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </template>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/fish-training.js') }}?v=1.0.1"></script>
@endsection
