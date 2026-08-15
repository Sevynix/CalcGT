@extends('layouts.app')
@section('title', "Level Calculator")
@section('meta_description', 'Calculate XP requirements and EXP potion usage to reach your desired player level in Growtopia.')

@section('content')
<div x-data="levelCalculator()" @keydown.window.enter.prevent="
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
                    <img loading="lazy" src="{{ asset('images/items/exp_potion.png') }}" alt="EXP Potion" class="w-10 h-10 object-contain drop-shadow-sm">
                    Level Calculator
                    <span
                        class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Player Tools</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Calculate EXP requirements between levels and formulate your journey to max level in Growtopia</p>

            </div>

            <!-- Quick Actions -->
            <div class="flex gap-3">
                <button @click="$store.modal.show('This will clear all your inputs and saved data.', () => { currentLevel = 0; targetLevel = 125; currentExp = 0; })"
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
                        <img loading="lazy" src="{{ asset('images/items/exp_potion.png') }}" alt="EXP Settings" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Level Settings</h2>
                </div>

                <div class="space-y-6">
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Current Level</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="0" max="124" x-model.number="currentLevel"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="0">
                            <span class="text-slate-500 font-bold text-[15px] ml-2 shrink-0">/ 124</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Target Level</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="1" max="125" x-model.number="targetLevel"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="125">
                            <span class="text-slate-500 font-bold text-[15px] ml-2 shrink-0">/ 125</span>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2 truncate" title="Current EXP">Current EXP</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
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
                    class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-blue-400/10 to-teal-400/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img loading="lazy" src="{{ asset('images/items/exclamation.png') }}" alt="Exclamation" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                        <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">EXP Overview</h2>
                    </div>

                    <!-- 3-Item Stats Layout -->
                    <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Total EXP Needed -->
                        <div
                            class="bg-brand-50 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-700/50 rounded-[20px] p-5 flex flex-col justify-center items-center text-center md:col-span-2 shadow-sm">
                            <p class="text-[13px] font-bold text-brand-500 dark:text-brand-400 uppercase tracking-widest mb-1.5">Total EXP Needed
                            </p>
                            <p class="text-[36px] font-extrabold text-brand-700 dark:text-brand-300 leading-none" x-text="formatNumber(calculateTotalExpNeeded())"></p>
                            <p class="text-[13px] text-brand-600/70 dark:text-brand-400/70 mt-2 font-medium">From your current progress to reach level <span x-text="targetLevel"></span></p>
                        </div>

                        <!-- Levels to Gain -->
                        <div
                            class="bg-slate-50 border border-slate-100 rounded-[20px] p-5 flex flex-col justify-center">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Levels to Gain</p>
                            <p class="text-[24px] font-extrabold text-slate-800 leading-none" x-text="(targetLevel - currentLevel > 0) ? (targetLevel - currentLevel) : '0'"></p>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">Remaining to progress</p>
                        </div>

                        <!-- To Next Level -->
                        <div
                            class="bg-white border border-slate-200 dark:border-[#2a3449] rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-left">
                            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">To Next Level
                            </p>
                            <p class="text-[24px] font-extrabold leading-none text-slate-800" x-text="formatNumber(calculateExpToNextLevel())"></p>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium">Remaining for level <span x-text="Number(currentLevel) + 1"></span></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Ghost Capturing Section -->
        <div x-data="{ openGhosts: false }" class="glass-card rounded-[32px] overflow-hidden shadow-inner-light shadow-sm border border-slate-100">
            <!-- Accordion Header -->
            <button @click="openGhosts = !openGhosts" class="w-full flex items-center justify-between p-6 lg:px-8 bg-white/40 dark:bg-[#151b2b]/40 hover:bg-white/60 dark:hover:bg-[#151b2b]/60 transition-colors text-left focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img loading="lazy" src="{{ asset('images/boo/jar.png') }}" alt="Ghost Jar" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-800 tracking-tight">Ghost Capturing Mode</h2>
                        <p class="text-[13px] text-slate-500 font-medium">Toggle active buffs to calculate exactly how many ghosts you'll need.</p>
                    </div>
                </div>
                <div class="p-2 rounded-full bg-slate-100 dark:bg-[#0b0f19] text-slate-500 transition-transform duration-300" :class="openGhosts ? 'rotate-180' : ''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </button>

            <!-- Expanded Content -->
            <div x-show="openGhosts"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="flex flex-col lg:flex-row border-t border-slate-100 dark:border-[#1c2438] bg-white/30 dark:bg-[#151b2b]/30"
                 style="display: none;">
                
                <!-- Left Side: Buffs -->
                <div class="w-full lg:w-2/3 p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-[#1c2438]">

                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3 mb-5">
                        
                        <!-- Ghost Dragon Charm -->
                        <label class="relative flex flex-col items-center justify-center p-3 bg-white dark:bg-[#151b2b] border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden"
                            :class="buffs.charm ? 'border-brand-400 bg-brand-50/30 dark:bg-brand-900/20 ring-1 ring-brand-400' : 'border-slate-200 dark:border-[#2a3449] hover:border-slate-300 dark:hover:border-[#3a475e] hover:bg-slate-50 dark:hover:bg-[#1c2438]'">
                            <input type="checkbox" x-model="buffs.charm" class="sr-only">
                            <div x-show="buffs.charm" class="absolute top-2 right-2 text-brand-500" style="display: none;">
                                <svg class="w-4 h-4 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <img loading="lazy" src="{{ asset('images/items/xp/ghost_dragon_charm.png') }}" alt="Ghost Dragon Charm" 
                                class="w-10 h-10 object-contain mb-2 transition-transform duration-300" 
                                :class="buffs.charm ? 'scale-110 drop-shadow-md' : 'grayscale-[40%] opacity-80'">
                            <span class="text-[12px] font-bold text-center leading-tight mb-1" :class="buffs.charm ? 'text-brand-700 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400'">Ghost Charm</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-center w-full" :class="buffs.charm ? 'bg-brand-100/80 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'">+200 BASE EXP</span>
                        </label>

                        <!-- Coconut Tart -->
                        <label class="relative flex flex-col items-center justify-center p-3 bg-white dark:bg-[#151b2b] border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden"
                            :class="buffs.tart ? 'border-brand-400 bg-brand-50/30 dark:bg-brand-900/20 ring-1 ring-brand-400' : 'border-slate-200 dark:border-[#2a3449] hover:border-slate-300 dark:hover:border-[#3a475e] hover:bg-slate-50 dark:hover:bg-[#1c2438]'">
                            <input type="checkbox" x-model="buffs.tart" class="sr-only">
                            <div x-show="buffs.tart" class="absolute top-2 right-2 text-brand-500" style="display: none;">
                                <svg class="w-4 h-4 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <img loading="lazy" src="{{ asset('images/items/xp/coconut_tart.png') }}" alt="Coconut Tart" 
                                class="w-10 h-10 object-contain mb-2 transition-transform duration-300" 
                                :class="buffs.tart ? 'scale-110 drop-shadow-md' : 'grayscale-[40%] opacity-80'">
                            <span class="text-[12px] font-bold text-center leading-tight mb-1" :class="buffs.tart ? 'text-brand-700 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400'">Coconut Tart</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-center w-full" :class="buffs.tart ? 'bg-brand-100/80 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'">+50 BASE EXP</span>
                        </label>

                        <!-- Ring of Wisdom -->
                        <label class="relative flex flex-col items-center justify-center p-3 bg-white dark:bg-[#151b2b] border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden"
                            :class="buffs.ring ? 'border-emerald-400 bg-emerald-50/30 dark:bg-emerald-900/20 ring-1 ring-emerald-400' : 'border-slate-200 dark:border-[#2a3449] hover:border-slate-300 dark:hover:border-[#3a475e] hover:bg-slate-50 dark:hover:bg-[#1c2438]'">
                            <input type="checkbox" x-model="buffs.ring" class="sr-only">
                            <div x-show="buffs.ring" class="absolute top-2 right-2 text-emerald-500" style="display: none;">
                                <svg class="w-4 h-4 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <img loading="lazy" src="{{ asset('images/items/xp/ring_of_wisdom.png') }}" alt="Ring of Wisdom" 
                                class="w-10 h-10 object-contain mb-2 transition-transform duration-300" 
                                :class="buffs.ring ? 'scale-110 drop-shadow-md' : 'grayscale-[40%] opacity-80'">
                            <span class="text-[12px] font-bold text-center leading-tight mb-1" :class="buffs.ring ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400'">Wisdom Ring</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-center w-full" :class="buffs.ring ? 'bg-emerald-100/80 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'">+10% EXP AVG</span>
                        </label>

                        <!-- Gingerbread Cookie -->
                        <label class="relative flex flex-col items-center justify-center p-3 bg-white dark:bg-[#151b2b] border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden"
                            :class="buffs.cookie ? 'border-emerald-400 bg-emerald-50/30 dark:bg-emerald-900/20 ring-1 ring-emerald-400' : 'border-slate-200 dark:border-[#2a3449] hover:border-slate-300 dark:hover:border-[#3a475e] hover:bg-slate-50 dark:hover:bg-[#1c2438]'">
                            <input type="checkbox" x-model="buffs.cookie" class="sr-only">
                            <div x-show="buffs.cookie" class="absolute top-2 right-2 text-emerald-500" style="display: none;">
                                <svg class="w-4 h-4 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <img loading="lazy" src="{{ asset('images/items/xp/gingerbread.png') }}" alt="Gingerbread Cookie" 
                                class="w-10 h-10 object-contain mb-2 transition-transform duration-300" 
                                :class="buffs.cookie ? 'scale-110 drop-shadow-md' : 'grayscale-[40%] opacity-80'">
                            <span class="text-[12px] font-bold text-center leading-tight mb-1" :class="buffs.cookie ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400'">Gingerbread</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-center w-full" :class="buffs.cookie ? 'bg-emerald-100/80 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'">+20% EXP AVG</span>
                        </label>

                        <!-- Biotronic Brain -->
                        <label class="relative flex flex-col items-center justify-center p-3 bg-white dark:bg-[#151b2b] border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden"
                            :class="buffs.brain ? 'border-emerald-400 bg-emerald-50/30 dark:bg-emerald-900/20 ring-1 ring-emerald-400' : 'border-slate-200 dark:border-[#2a3449] hover:border-slate-300 dark:hover:border-[#3a475e] hover:bg-slate-50 dark:hover:bg-[#1c2438]'">
                            <input type="checkbox" x-model="buffs.brain" class="sr-only">
                            <div x-show="buffs.brain" class="absolute top-2 right-2 text-emerald-500" style="display: none;">
                                <svg class="w-4 h-4 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <img loading="lazy" src="{{ asset('images/items/xp/biotronic.png') }}" alt="Biotronic Brain" 
                                class="w-10 h-10 object-contain mb-2 transition-transform duration-300" 
                                :class="buffs.brain ? 'scale-110 drop-shadow-md' : 'grayscale-[40%] opacity-80'">
                            <span class="text-[12px] font-bold text-center leading-tight mb-1" :class="buffs.brain ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400'">Biotronic Brain</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-center w-full" :class="buffs.brain ? 'bg-emerald-100/80 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'">+50% EXP AVG</span>
                        </label>
                    </div>

                <div class="flex flex-col mt-4 w-full border-t border-slate-100 pt-5">
                    <label class="text-slate-600 font-bold text-[14px] mb-3 flex justify-between items-center">
                        <span class="flex items-center gap-2">
                            Ancestral Totem Level
                            <span class="text-[10px] text-slate-400 font-medium font-normal hidden sm:inline">(Select one)</span>
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md text-center transition-colors" 
                              :class="Number(buffs.totemLevel) > 0 ? 'bg-emerald-100/80 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'" 
                              x-text="Number(buffs.totemLevel) > 0 ? '+' + (Number(buffs.totemLevel) + 4) + '% EXP AVG' : 'Unused'"></span>
                    </label>
                    
                    <div class="grid grid-cols-3 md:grid-cols-6 gap-2 sm:gap-3">
                        <template x-for="lvl in [1, 2, 3, 4, 5, 6]" :key="lvl">
                            <label class="relative flex flex-col items-center justify-center p-2 sm:p-3 bg-white dark:bg-[#151b2b] border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden"
                                :class="Number(buffs.totemLevel) === lvl ? 'border-emerald-400 bg-emerald-50/30 dark:bg-emerald-900/20 ring-1 ring-emerald-400' : 'border-slate-200 dark:border-[#2a3449] hover:border-slate-300 dark:hover:border-[#3a475e] hover:bg-slate-50 dark:hover:bg-[#1c2438]'">
                                
                                <input type="radio" name="totemLevel" :value="lvl" x-model.number="buffs.totemLevel" class="sr-only" 
                                    @click="if (buffs.totemLevel === lvl) { $event.preventDefault(); buffs.totemLevel = 0; }">
                                
                                <div x-show="Number(buffs.totemLevel) === lvl" class="absolute top-1.5 right-1.5 text-emerald-500" style="display: none;">
                                    <svg class="w-3.5 h-3.5 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>

                                <img loading="lazy" :src="lvl > 1 ? '{{ asset('images/items/xp/ances_') }}' + lvl + '.png' : '{{ asset('images/items/xp/ances.png') }}'" 
                                    :alt="'Totem Level ' + lvl"
                                    class="w-8 h-8 sm:w-10 sm:h-10 object-contain mb-1.5 sm:mb-2 transition-transform duration-300" 
                                    :class="Number(buffs.totemLevel) === lvl ? 'scale-110 drop-shadow-md' : 'grayscale-[40%] opacity-80'">
                                
                                <span class="text-[11px] sm:text-[12px] font-bold text-center leading-tight mb-1" 
                                      :class="Number(buffs.totemLevel) === lvl ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400'" 
                                      x-text="'Level ' + lvl"></span>
                                      
                                <span class="text-[9px] sm:text-[10px] font-bold px-1 py-0.5 rounded-md text-center w-full" 
                                      :class="Number(buffs.totemLevel) === lvl ? 'bg-emerald-100/80 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'" 
                                      x-text="'+' + (lvl + 4) + '%'"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right Side: The Result Box -->
            <div class="w-full lg:w-1/3 p-8 bg-slate-800 text-white flex flex-col justify-center relative overflow-hidden group">
                <div class="absolute -right-16 -top-16 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/30 transition-colors duration-700 pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col justify-center h-full text-center lg:text-left">
                    <div class="flex flex-col lg:flex-row lg:justify-between items-center lg:items-start mb-3 gap-3">
                        <p class="text-[13px] font-bold text-white/70 uppercase tracking-widest mt-1">Ghosts to Capture</p>
                        <span class="bg-indigo-500/30 text-indigo-100 text-[11px] font-bold px-2.5 py-1 rounded-full border border-indigo-400/30 shadow-sm whitespace-nowrap" x-text="getGhostExp() + ' EXP/Ea'"></span>
                    </div>
                    <p class="text-[48px] xl:text-[42px] lg:text-[40px] md:text-[48px] font-extrabold leading-none tracking-tight text-white drop-shadow-md mb-3" x-text="getGhostsNeeded()"></p>
                    <p class="text-[12px] text-white/60 leading-relaxed max-w-[250px] mx-auto lg:mx-0 mb-4">Calculated by dividing the total EXP needed by your custom buffed ghost value.</p>
                    
                    <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-3 flex items-start gap-2 text-left mt-auto">
                        <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-[10.5px] text-amber-200/90 leading-relaxed">
                            <span class="font-bold text-amber-400">Note:</span> 
                            This calculation is an estimation and is not 100% accurate, so use these estimates wisely!
                        </p>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <!-- The Table Area -->
        <div x-data="{ openTable: false }" class="glass-card rounded-[32px] overflow-hidden shadow-inner-light shadow-sm border border-slate-100">
            <!-- Accordion Header -->
            <button @click="openTable = !openTable" class="w-full flex items-center justify-between p-6 lg:px-8 bg-white/40 dark:bg-[#151b2b]/40 hover:bg-white/60 dark:hover:bg-[#151b2b]/60 transition-colors text-left focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img loading="lazy" src="{{ asset('images/items/scroll.png') }}" alt="Scroll Bulletin" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-800 tracking-tight">Level Breakdown Table</h2>
                        <p class="text-[13px] text-slate-500 font-medium">EXP requirements perfectly mapped out for your journey.</p>
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
                            <th class="px-4 py-3 uppercase">Level</th>
                            <th class="px-4 py-3 uppercase">EXP Needed</th>
                            <th class="px-4 py-3 uppercase text-right">Cumulative Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(exp, index) in getVisibleBreakdown()" :key="index + currentLevel + 1">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-[#111622]/50 transition-colors group bg-white dark:bg-[#151b2b]">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#0b0f19] text-slate-600 dark:text-slate-400 border-transparent dark:border-[#2a3449] font-bold flex items-center justify-center text-xs border border-slate-200 dark:border-[#2a3449]">
                                            <span x-text="index + Number(currentLevel) + 1"></span>
                                        </div>
                                        <span class="font-bold text-slate-800">Level <span x-text="index + Number(currentLevel) + 1"></span></span>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 bg-brand-50 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300 px-3 py-1 rounded-lg font-bold text-[13px]">
                                        +<span x-text="formatNumber(exp.needed)"></span> EXP
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <span class="font-bold text-[14.5px] text-slate-800 block pt-1"
                                        x-text="formatNumber(exp.total)"></span>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="getVisibleBreakdown().length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500 font-medium">
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
<script src="{{ asset('js/level-calculator.js') }}?v=1.0.1"></script>
@endsection

