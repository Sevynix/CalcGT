@extends('layouts.app')
@section('title', "Roles Calculator")
@section('meta_description', "Plan role progressions, XP requirements, and perk unlock milestones for Growtopia's role system.")

@section('content')
<div x-data="rolesCalculator()" @keydown.window.enter.prevent="
    let inputs = Array.from(document.querySelectorAll('input:not([type=radio]):not([type=checkbox])'));
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
                    <img loading="lazy" src="{{ asset('images/roles/farmer.png') }}" alt="Roles Calculator" class="w-10 h-10 object-contain drop-shadow-sm">
                    Roles Calculator
                    <span
                        class="bg-gradient-to-r from-brand-400 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Player Tools</span>
                </h1>
                <p class="text-slate-500 mt-2 max-w-lg">Calculate Role Points and estimate the total amount of quests needed to reach the max level.</p>

            </div>

            <!-- Quick Actions -->
            <div class="flex gap-3">
                <button @click="$store.modal.show('This will clear all your inputs and saved data.', () => { currentLevel = 0; targetLevel = 10; currentPoints = 0; buffs.jack=false; buffs.supplier_cape=false; buffs.crafter_cape=false; })"
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

        <!-- Role Selection Area -->
        <div class="glass-card rounded-[32px] p-6 lg:p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-[20px] font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                        <span>Select Role Target</span>
                        <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md" x-text="getSelectedRole().type === 'supplier' ? 'Supplier Class' : 'Crafter Class'"></span>
                    </h2>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                    <template x-for="role in rolesList" :key="role.id">
                        <label class="relative flex flex-col items-center justify-center p-3 bg-white border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden"
                            :class="selectedRoleId === role.id ? 'border-brand-400 bg-brand-50/30 ring-1 ring-brand-400' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                            
                            <input type="radio" name="selectedRole" :value="role.id" x-model="selectedRoleId" class="sr-only">
                            
                            <div x-show="selectedRoleId === role.id" class="absolute top-2 right-2 text-brand-500" style="display: none;">
                                <svg class="w-4 h-4 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>

                            <img loading="lazy" :src="asset('images/roles/' + role.img)" :alt="role.name"
                                class="w-12 h-12 object-contain mb-2 transition-transform duration-300 drop-shadow-md" 
                                :class="selectedRoleId === role.id ? 'scale-110' : 'grayscale-[20%] opacity-80'">
                            
                            <span class="text-[12px] font-bold text-center leading-tight mb-1 text-slate-700" 
                                  x-text="role.name"></span>
                        </label>
                    </template>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Input Card -->
            <div class="glass-card rounded-[32px] p-8 shadow-sm border border-slate-100 lg:col-span-1 flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img loading="lazy" src="{{ asset('images/items/downarrow.png') }}" alt="Down Arrow" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Setup</h2>
                </div>

                <div class="space-y-6 flex-grow">
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Current Role Level</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="0" max="9" step="1" x-model="currentLevel"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="0">
                            <span class="text-slate-500 font-bold text-[15px] ml-2 shrink-0">/ 9</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Target Role Level</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="1" max="10" step="1" x-model="targetLevel"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="10">
                            <span class="text-slate-500 font-bold text-[15px] ml-2 shrink-0">/ 10</span>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Current Points</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="0" step="1" x-model="currentPoints"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="0">
                            <span class="text-slate-500 font-bold text-[13px] ml-2 shrink-0">Pts / <span x-text="formatNumber(getPointsForNextLevel(Number(currentLevel)))"></span></span>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-slate-600 font-bold text-[15px] mb-2">Quests Per Day</label>
                        <div
                            class="flex items-center bg-white border border-slate-200 dark:border-[#2a3449] rounded-xl px-3 py-2.5 shadow-sm focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all w-full">
                            <input type="number" min="1" max="10" step="1" x-model="questsPerDay"
                                class="bg-transparent text-slate-800 text-[16px] font-medium outline-none w-full p-0 m-0"
                                placeholder="1">
                            <span class="text-slate-500 font-bold text-[13px] ml-2 shrink-0">Max 10</span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">Cost multiplier applied after the 1st free quest.</p>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div
                class="bg-white rounded-[32px] p-8 lg:col-span-2 shadow-sm border border-slate-100 flex flex-col justify-between relative overflow-hidden group">

                <!-- Background Decoration -->
                <div
                    class="absolute -right-20 -top-20 w-64 h-64 bg-gradient-to-br from-brand-400/10 to-blue-400/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img loading="lazy" src="{{ asset('images/items/exclamation.png') }}" alt="Exclamation" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                            <h2 class="text-[22px] font-extrabold text-slate-800 tracking-tight">Progress Calculation</h2>
                        </div>
                    </div>

                    <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Total Points Needed -->
                        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-700/50 rounded-[20px] p-5 flex flex-col justify-center items-center text-center shadow-sm relative overflow-hidden md:col-span-2">
                            <p class="text-[13px] font-bold uppercase tracking-widest mb-1.5 relative z-10 text-emerald-500 dark:text-emerald-400">Total Points Needed</p>
                            <div class="flex items-center gap-2 relative z-10">
                                <p class="text-[36px] lg:text-[42px] font-extrabold leading-none text-emerald-700 dark:text-emerald-400" x-text="formatNumber(calculateTotalPointsNeeded())"></p>
                                <span class="bg-white/60 dark:bg-emerald-500/20 text-[13px] font-bold px-2 py-0.5 rounded-full text-emerald-600 dark:text-emerald-300 shadow-sm">Pts</span>
                            </div>
                            <p class="text-[13px] mt-2 font-medium relative z-10 text-emerald-600/70">To push your <span class="text-emerald-700 font-bold" x-text="getSelectedRole().name"></span> role from level <span class="text-emerald-700 font-bold" x-text="currentLevel"></span> to <span class="text-emerald-700 font-bold" x-text="targetLevel"></span></p>
                        </div>

                        <!-- Total Quests Needed -->
                        <div class="bg-brand-50 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-700/50 rounded-[20px] p-5 flex flex-col justify-center transform transition hover:-translate-y-1 text-left relative">
                            <div class="absolute top-4 right-4" x-show="getMultiplier() > 1.0" style="display: none;">
                                <span class="bg-brand-100 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300 text-[11px] font-bold px-2.5 py-1 rounded-lg border border-brand-200 dark:border-brand-500/30" x-text="'x' + getMultiplier().toFixed(2)"></span>
                            </div>
                            <p class="text-[12px] font-bold text-brand-500 dark:text-brand-400 uppercase tracking-widest mb-1.5">Total Quests Needed</p>
                            <div class="flex items-end gap-2">
                                <p class="text-[28px] font-extrabold leading-none text-brand-700 dark:text-brand-300" x-text="formatNumber(calculateTotalQuestsNeeded())"></p>
                                <p class="text-[14px] font-bold text-brand-600/70 dark:text-brand-400/70 mb-1">Quests</p>
                            </div>
                            <p class="text-[12px] text-brand-600/70 dark:text-brand-400/70 mt-2 font-medium leading-tight whitespace-nowrap overflow-hidden text-ellipsis" x-text="getMultiplier() > 1.0 ? 'Active bonus reducing quest requirements' : 'Standard 1x progression rate'"></p>
                        </div>

                        <!-- Daily Gem Cost -->
                        <div class="bg-slate-50 border border-slate-200 dark:border-[#2a3449] rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-left">
                            <p class="text-[12px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Daily Gem Cost</p>
                            <div class="flex items-center gap-2">
                                <p class="text-[24px] font-extrabold leading-none text-slate-700" x-text="formatNumber(getDailyGemCost(Number(questsPerDay)))"></p>
                                <img loading="lazy" src="{{ asset('images/items/gems.png') }}" class="w-7 h-7 lg:w-8 lg:h-8 object-contain drop-shadow-sm" alt="Gems">
                            </div>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium leading-tight">At <span x-text="questsPerDay"></span> quest(s) per day</p>
                        </div>

                        <!-- Est Days Needed -->
                        <div class="bg-slate-50 border border-slate-200 dark:border-[#2a3449] rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-left">
                            <p class="text-[12px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Total Days Required</p>
                            <p class="text-[24px] font-extrabold leading-none text-slate-700" x-text="formatNumber(calculateTotalDaysNeeded())"></p>
                            <p class="text-[12px] text-slate-500 mt-2 font-medium leading-tight">At <span x-text="questsPerDay"></span> quest(s) per day</p>
                        </div>
                        
                        <!-- Est Gem Cost -->
                        <div class="bg-brand-50 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-700/50 rounded-[20px] p-5 shadow-sm flex flex-col justify-center transform transition hover:-translate-y-1 text-left">
                            <p class="text-[12px] font-bold text-brand-500 dark:text-brand-400 uppercase tracking-widest mb-1.5">Total Gem Cost</p>
                            <div class="flex items-center gap-2">
                                <p class="text-[24px] font-extrabold leading-none text-brand-700 dark:text-brand-300" x-text="formatNumber(calculateTotalGemCost())"></p>
                                <img loading="lazy" src="{{ asset('images/items/gems.png') }}" class="w-7 h-7 lg:w-8 lg:h-8 object-contain drop-shadow-sm" alt="Gems">
                            </div>
                            <p class="text-[12px] text-brand-600/70 dark:text-brand-400/70 mt-2 font-medium leading-tight">Daily cost Ã— <span x-text="formatNumber(calculateTotalDaysNeeded())"></span> days</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Bonus Settings Area -->
        <div x-data="{ openBonuses: false }" class="glass-card rounded-[32px] overflow-hidden shadow-inner-light shadow-sm border border-slate-100">
            <!-- Accordion Header -->
            <button @click="openBonuses = !openBonuses" class="w-full flex items-center justify-between p-6 lg:px-8 bg-white/40 dark:bg-[#151b2b]/40 hover:bg-white/60 dark:hover:bg-[#151b2b]/60 transition-colors text-left focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0 shadow-inner border border-slate-200 dark:border-[#2a3449]">
                        <img loading="lazy" src="{{ asset('images/items/legendorb.png') }}" alt="Legendary Orb" class="w-8 h-8 object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-800 tracking-tight">Active Multipliers</h2>
                        <p class="text-[13px] text-slate-500 font-medium">Equip Capes or Daily factors to speed up your quest points.</p>
                    </div>
                </div>
                <div class="p-2 rounded-full bg-slate-100 dark:bg-[#0b0f19] text-slate-500 transition-transform duration-300" :class="openBonuses ? 'rotate-180' : ''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </button>

            <!-- Expanded Content -->
            <div x-show="openBonuses"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="border-t border-slate-100 dark:border-[#1c2438] bg-white/30 dark:bg-[#151b2b]/30"
                 style="display: none;">
                
                <div class="p-6 lg:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        
                        <!-- Jack's Set -->
                        <label class="relative flex flex-row items-center p-4 bg-white dark:bg-[#151b2b] border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden group"
                            :class="buffs.jack ? 'border-brand-400 bg-brand-50/30 dark:bg-brand-900/20 ring-1 ring-brand-400' : 'border-slate-200 dark:border-[#2a3449] hover:border-slate-300 dark:hover:border-[#3a475e] hover:bg-slate-50 dark:hover:bg-[#1c2438]'">
                            <input type="checkbox" x-model="buffs.jack" class="sr-only">
                            <img loading="lazy" src="{{ asset('images/roles/item/jack_hat.png') }}" alt="Jack's Set" class="w-10 h-10 object-contain mr-4 drop-shadow-sm transition-transform duration-300 group-hover:scale-110" :class="buffs.jack ? 'scale-110' : 'grayscale-[20%] opacity-80'">
                            <div class="flex-grow">
                                <span class="text-[15px] font-bold block mb-1" :class="buffs.jack ? 'text-brand-700 dark:text-brand-400' : 'text-slate-800 dark:text-slate-200'">Jack's Set</span>
                                <span class="text-[12px] font-medium" :class="buffs.jack ? 'text-brand-600/70 dark:text-brand-400/70' : 'text-slate-500 dark:text-slate-400'">Full Hat, Vest, Jeans, & Shoes</span>
                            </div>
                            <div class="flex-shrink-0 ml-3">
                                <span class="text-[12px] font-bold px-2.5 py-1 rounded-lg text-center transition-colors" :class="buffs.jack ? 'bg-brand-100/80 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'">+3%</span>
                            </div>
                        </label>

                        <!-- Supplier's Cape -->
                        <label class="relative flex flex-row items-center p-4 bg-white dark:bg-[#151b2b] border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden group"
                            :class="buffs.supplier_cape ? 'border-brand-400 bg-brand-50/30 dark:bg-brand-900/20 ring-1 ring-brand-400' : 'border-slate-200 dark:border-[#2a3449] hover:border-slate-300 dark:hover:border-[#3a475e] hover:bg-slate-50 dark:hover:bg-[#1c2438]'">
                            <input type="checkbox" x-model="buffs.supplier_cape" class="sr-only">
                            <img loading="lazy" src="{{ asset('images/roles/item/supplier_cape.png') }}" alt="Supplier's Cape" class="w-10 h-10 object-contain mr-4 drop-shadow-sm transition-transform duration-300 group-hover:scale-110" :class="buffs.supplier_cape ? 'scale-110' : 'grayscale-[20%] opacity-80'">
                            <div class="flex-grow">
                                <span class="text-[15px] font-bold block mb-1" :class="buffs.supplier_cape ? 'text-brand-700 dark:text-brand-400' : 'text-slate-800 dark:text-slate-200'">Supplier's Cape</span>
                                <span class="text-[12px] font-medium" :class="buffs.supplier_cape ? 'text-brand-600/70 dark:text-brand-400/70' : 'text-slate-500 dark:text-slate-400'">+5% for Supplier quests</span>
                            </div>
                            <div class="flex-shrink-0 ml-3">
                                <span class="text-[12px] font-bold px-2.5 py-1 rounded-lg text-center transition-colors" :class="buffs.supplier_cape ? 'bg-brand-100/80 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'">+5%</span>
                            </div>
                        </label>

                        <!-- Crafter's Cape -->
                        <label class="relative flex flex-row items-center p-4 bg-white dark:bg-[#151b2b] border cursor-pointer transition-all shadow-sm rounded-2xl overflow-hidden group"
                            :class="buffs.crafter_cape ? 'border-brand-400 bg-brand-50/30 dark:bg-brand-900/20 ring-1 ring-brand-400' : 'border-slate-200 dark:border-[#2a3449] hover:border-slate-300 dark:hover:border-[#3a475e] hover:bg-slate-50 dark:hover:bg-[#1c2438]'">
                            <input type="checkbox" x-model="buffs.crafter_cape" class="sr-only">
                            <img loading="lazy" src="{{ asset('images/roles/item/crafter_cape.png') }}" alt="Crafter's Cape" class="w-10 h-10 object-contain mr-4 drop-shadow-sm transition-transform duration-300 group-hover:scale-110" :class="buffs.crafter_cape ? 'scale-110' : 'grayscale-[20%] opacity-80'">
                            <div class="flex-grow">
                                <span class="text-[15px] font-bold block mb-1" :class="buffs.crafter_cape ? 'text-brand-700 dark:text-brand-400' : 'text-slate-800 dark:text-slate-200'">Crafter's Cape</span>
                                <span class="text-[12px] font-medium" :class="buffs.crafter_cape ? 'text-brand-600/70 dark:text-brand-400/70' : 'text-slate-500 dark:text-slate-400'">+5% for Crafter quests</span>
                            </div>
                            <div class="flex-shrink-0 ml-3">
                                <span class="text-[12px] font-bold px-2.5 py-1 rounded-lg text-center transition-colors" :class="buffs.crafter_cape ? 'bg-brand-100/80 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300' : 'bg-slate-100 dark:bg-[#0b0f19] text-slate-500 dark:text-slate-400'">+5%</span>
                            </div>
                        </label>

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
                        <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-800 tracking-tight">Progression Breakdown</h2>
                        <p class="text-[13px] text-slate-500 font-medium">Per-level points and quest estimations perfectly mapped out.</p>
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
                            <th class="px-4 py-3 uppercase">Target Level</th>
                            <th class="px-4 py-3 uppercase">Points Needed</th>
                            <th class="px-4 py-3 uppercase">Est. Quests</th>
                            <th class="px-4 py-3 uppercase">Days / Gems</th>
                            <th class="px-4 py-3 uppercase text-right">Cumulative Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(req, index) in getVisibleBreakdown()" :key="req.levelTo">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-[#111622]/50 transition-colors group bg-white dark:bg-[#151b2b]">
                                
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#0b0f19] text-slate-600 dark:text-slate-400 border-transparent dark:border-[#2a3449] font-bold flex items-center justify-center text-[12px] border border-slate-200 dark:border-[#2a3449]">
                                            <span x-text="req.levelTo"></span>
                                        </div>
                                        <span class="font-bold text-slate-800">Level <span x-text="req.levelTo"></span></span>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-extrabold text-slate-700 text-[14px]">
                                            <span x-text="formatNumber(req.pointsNeeded)"></span> Pts
                                        </span>
                                        <span class="text-[11px] font-bold opacity-60 flex items-center gap-1" :class="'text-' + getSelectedRole().color + '-600'">
                                            +<span x-text="formatNumber(req.questYield)"></span> pts/quest
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 bg-brand-50 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300 px-3 py-1 rounded-lg font-bold text-[13px]">
                                        <span x-text="formatNumber(req.questsNeeded)"></span> Quests
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700 text-[13px]">
                                            <span x-text="formatNumber(req.daysNeeded)"></span> Days
                                        </span>
                                        <span class="font-bold text-[11px]" :class="req.gemCost > 0 ? 'text-rose-500' : 'text-slate-400'">
                                            <span x-text="formatNumber(req.gemCost)"></span> Gems
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <span class="font-bold text-[14.5px] text-slate-800 block pt-1"
                                        x-text="formatNumber(req.cumulativePhaseCost) + ' Pts'"></span>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="getVisibleBreakdown().length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">
                                Your target level must be higher than your current level to see the progression mapped out.
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function asset(path) {
        return path.startsWith('/') ? path : '/' + path;
    }
</script>
@endsection

@section('scripts')
<script src="{{ asset('js/roles-calc.js') }}?v=1.0.2"></script>
@endsection

