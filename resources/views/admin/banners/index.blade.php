@extends('layouts.admin')

@section('title', 'Manage Ad Slots | Admin Dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Add any custom Flatpickr styling here if needed */
    .flatpickr-calendar {
        border: none !important;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        border-radius: 1rem !important;
        padding-bottom: 0.75rem !important;
    }
    
    /* Remove range block and hover background on dates */
    .flatpickr-day.inRange,
    .flatpickr-day.inRange:hover,
    .flatpickr-day:hover {
        background: transparent !important;
        border-color: transparent !important;
        box-shadow: none !important;
        color: #393939 !important; /* Keep text color solid */
    }
    
    /* Keep start and end dates highlighted */
    .flatpickr-day.startRange, 
    .flatpickr-day.endRange, 
    .flatpickr-day.startRange:hover, 
    .flatpickr-day.endRange:hover {
        background: #3b82f6 !important; /* Tailwind blue-500 */
        border-color: #3b82f6 !important;
        color: white !important;
    }
</style>
@endsection

@section('content')
<div class="space-y-6" x-data="bannerManager">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manage Ad Slots</h1>
        <p class="text-slate-500 font-medium mt-1">Schedule and manage promotional banners for your dashboard.</p>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside text-sm font-medium ml-8">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- SECTION A: Schedule New Rental Form -->
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-extrabold text-slate-900">Schedule New Rental</h2>
            <button type="button" @click.prevent="openCalendarModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                View Availability
            </button>
        </div>
        
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Details -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Target Slot</label>
                        <select name="position" x-model="createSlot" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-slate-700" required>
                            <option value="1">Slot 1 (Large Hero)</option>
                            <option value="2">Slot 2 (Small Top)</option>
                            <option value="3">Slot 3 (Small Bottom)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Client/Campaign Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Summer Sale 2026" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Link URL (Optional)</label>
                        <input type="url" name="link_url" value="{{ old('link_url') }}" placeholder="https://..."
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Upload Banner Image</label>
                        <input type="file" name="image" accept="image/*" required @change="handleCreateImagePreview"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors file:cursor-pointer cursor-pointer">
                    </div>
                </div>

                <!-- Right Column: Schedule & Preview -->
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Start Date</label>
                            <input type="text" name="starts_at" x-ref="createStartCalendar" placeholder="Starts..." readonly required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-center cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">End Date</label>
                            <input type="text" name="ends_at" x-ref="createEndCalendar" placeholder="Ends..." readonly required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-center cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Image Preview</label>
                        <div class="aspect-[3/2] bg-slate-100 rounded-xl overflow-hidden border border-slate-200 flex items-center justify-center">
                            <template x-if="createPreview">
                                <img :src="createPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!createPreview">
                                <span class="text-slate-400 font-bold text-sm">No image selected</span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 relative"></div>
                    <span class="ml-2 text-sm font-bold text-slate-600">Active</span>
                </label>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all active:scale-95 shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Schedule Rental
                </button>
            </div>
        </form>
    </div>

    <!-- SECTION B: Scheduled Rentals List -->
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden p-6 mt-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h2 class="text-xl font-extrabold text-slate-900">Scheduled Rentals</h2>
            
            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-3">
                <select x-model="filterSlot" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-bold text-slate-700 transition-all cursor-pointer">
                    <option value="all">All Slots</option>
                    <option value="1">Slot 1 (Large)</option>
                    <option value="2">Slot 2 (Small Top)</option>
                    <option value="3">Slot 3 (Small Bottom)</option>
                </select>

                <select x-model="filterStatus" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-bold text-slate-700 transition-all cursor-pointer">
                    <option value="all">All Statuses</option>
                    <option value="active">Active Now</option>
                    <option value="upcoming">Scheduled</option>
                    <option value="expired">Expired</option>
                    <option value="inactive">Inactive (Kill Switch)</option>
                </select>
            </div>
        </div>
        @if($banners->isEmpty())
            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-slate-500 font-bold">No rentals scheduled yet.</p>
                <p class="text-slate-400 text-sm mt-1">Use the form above to create your first ad rental.</p>
            </div>
        @else
            <div class="overflow-x-auto pb-4">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Banner</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Details</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Slot</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Period</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($banners as $banner)
                            @php
                                $now = now();
                                $isActive = $banner->is_active && $now >= $banner->starts_at && $now <= $banner->ends_at;
                                $isUpcoming = $banner->is_active && $now < $banner->starts_at;
                                $isExpired = $now > $banner->ends_at;
                                
                                $statusStr = 'inactive';
                                if ($banner->is_active) {
                                    if ($isActive) $statusStr = 'active';
                                    elseif ($isUpcoming) $statusStr = 'upcoming';
                                    elseif ($isExpired) $statusStr = 'expired';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors group" x-show="showRow({{ $banner->position }}, '{{ $statusStr }}')">
                                <td class="py-3 px-4">
                                    <div class="w-24 h-16 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 relative">
                                        <img src="{{ asset($banner->image_path) }}" class="w-full h-full object-cover">
                                        @if(!$banner->is_active)
                                            <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center">
                                                <span class="text-[8px] font-bold text-white uppercase tracking-wider bg-red-500 px-1.5 py-0.5 rounded">Off</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-slate-800">{{ $banner->title }}</p>
                                    @if($banner->link_url)
                                        <a href="{{ $banner->link_url }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            Link
                                        </a>
                                    @else
                                        <p class="text-xs text-slate-400 mt-0.5">No link</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-slate-100 text-slate-600">
                                        Slot {{ $banner->position }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-xs font-medium text-slate-600">
                                    <div class="flex flex-col gap-1">
                                        <span class="flex items-center gap-1.5"><div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>{{ $banner->starts_at->format('M j, Y') }}</span>
                                        <span class="flex items-center gap-1.5"><div class="w-1.5 h-1.5 rounded-full bg-red-400"></div>{{ $banner->ends_at->format('M j, Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    @if(!$banner->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-slate-100 text-slate-500">Inactive</span>
                                    @elseif($isActive)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-700">Active Now</span>
                                    @elseif($isUpcoming)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-blue-100 text-blue-700">Upcoming</span>
                                    @elseif($isExpired)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700">Expired</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right flex justify-end gap-2">
                                    <button type="button" data-banner="{{ $banner->toJson() }}" @click.prevent="openEditModal(JSON.parse($event.currentTarget.dataset.banner))" class="text-slate-500 hover:text-blue-600 p-2 rounded-lg hover:bg-slate-100 transition-colors" title="Edit Rental">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to permanently delete this scheduled rental?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Delete Rental">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Live Site Preview -->
    <div class="mt-12 pt-8 border-t border-slate-200">
        <h2 class="text-2xl font-extrabold text-slate-900 mb-2">Live Site Preview</h2>
        <p class="text-slate-500 font-medium mb-6">This accurately reflects exactly what the public is seeing right now based on your active schedules.</p>
        
        @php
            $now = now();
            // Fetch currently active rentals for preview
            $activeBanners = \App\Models\Banner::where('is_active', true)
                ->where('starts_at', '<=', $now)
                ->where('ends_at', '>=', $now)
                ->get();
            $slot1 = $activeBanners->where('position', 1)->first();
            $slot2 = $activeBanners->where('position', 2)->first();
            $slot3 = $activeBanners->where('position', 3)->first();
            
            $defaultImage = asset('images/promos/banner.png');
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 p-4 md:p-6 rounded-[2.5rem] border border-slate-200/50">
            <!-- Slot 1 (Large) -->
            <div class="md:col-span-2 relative overflow-hidden rounded-[32px] bg-white shadow-sm flex items-center justify-center aspect-[3/2]">
                <img :src="createSlot == '1' && createPreview ? createPreview : '{{ $slot1 ? asset($slot1->image_path) : $defaultImage }}'" class="w-full h-full object-cover">
            </div>

            <div class="md:col-span-1 flex flex-row md:flex-col items-start md:items-stretch gap-3 md:gap-4 w-full h-full">
                <!-- Slot 2 (Small Top) -->
                <div class="relative overflow-hidden rounded-[32px] bg-white shadow-sm flex-1 flex items-center justify-center aspect-[3/2]">
                    <img :src="createSlot == '2' && createPreview ? createPreview : '{{ $slot2 ? asset($slot2->image_path) : $defaultImage }}'" class="w-full h-full object-cover">
                </div>

                <!-- Slot 3 (Small Bottom) -->
                <div class="relative overflow-hidden rounded-[32px] bg-white shadow-sm flex-1 flex items-center justify-center aspect-[3/2]">
                    <img :src="createSlot == '3' && createPreview ? createPreview : '{{ $slot3 ? asset($slot3->image_path) : $defaultImage }}'" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal (Popup) -->
    <div x-cloak x-show="isEditModalOpen" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div x-show="isEditModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto custom-scrollbar">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal panel -->
                <div x-show="isEditModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform text-left shadow-2xl transition-all sm:my-8 w-full sm:max-w-lg bg-white rounded-3xl border border-slate-200/60 flex flex-col max-h-[90vh]">
                    
                    <form :action="`/admin/banners/${editBanner.id}`" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
                        @csrf
                        @method('PUT')
                        
                        <!-- Header -->
                        <div class="p-6 sm:p-8 border-b border-slate-100 shrink-0">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-extrabold text-slate-900" id="modal-title">Edit Rental</h2>
                                <button type="button" @click.prevent="isEditModalOpen = false" class="text-slate-400 hover:text-slate-500 hover:bg-slate-100 p-2 rounded-full transition-colors focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Body (Scrollable) -->
                        <div class="p-6 sm:p-8 overflow-y-auto custom-scrollbar">
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Target Slot</label>
                                    <select name="position" x-model="editBanner.position" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-slate-700" required>
                                        <option value="1">Slot 1 (Large Hero)</option>
                                        <option value="2">Slot 2 (Small Top)</option>
                                        <option value="3">Slot 3 (Small Bottom)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Client/Campaign Title</label>
                                    <input type="text" name="title" x-model="editBanner.title" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Link URL (Optional)</label>
                                    <input type="url" name="link_url" x-model="editBanner.link_url" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Update Image</label>
                                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors file:cursor-pointer cursor-pointer p-1.5 border border-dashed border-slate-200 rounded-xl">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Start Date</label>
                                        <input type="text" name="starts_at" x-ref="editStartCalendar" placeholder="Starts..." readonly required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-center cursor-pointer">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">End Date</label>
                                        <input type="text" name="ends_at" x-ref="editEndCalendar" placeholder="Ends..." readonly required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium text-center cursor-pointer">
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-100">
                                    <label class="flex items-center cursor-pointer justify-between">
                                        <span class="text-sm font-bold text-slate-700">Active Status</span>
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="is_active" value="1" x-model="editBanner.is_active" class="sr-only peer">
                                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 relative"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="border-t border-slate-100 p-6 sm:p-8 sm:py-5 bg-slate-50 flex justify-end gap-3 shrink-0 rounded-b-3xl">
                            <button type="button" @click.prevent="isEditModalOpen = false" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors focus:outline-none">Cancel</button>
                            <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition-colors focus:outline-none">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calendar Modal -->
    <div x-cloak x-show="isCalendarModalOpen" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="isCalendarModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto p-4 sm:p-6 md:p-12 flex justify-center items-start custom-scrollbar">
            <div x-show="isCalendarModalOpen" @click.away="closeCalendarModal()" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform bg-white rounded-3xl shadow-2xl transition-all w-full border border-slate-200/60 overflow-hidden" style="max-width: 700px;">
                <!-- Header -->
                <div class="p-6 sm:p-8 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800">Slot Availability Calendar</h3>
                        <p class="text-sm font-medium text-slate-500 mt-0.5">Check exactly which dates are open for booking.</p>
                    </div>
                    <button type="button" @click="closeCalendarModal()" class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
                        <div class="flex items-center gap-3">
                            <label class="text-sm font-bold text-slate-600">Select Slot:</label>
                            <select x-model="calendarSlot" class="px-3 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm font-bold text-slate-700 transition-all cursor-pointer shadow-sm">
                                <option value="1">Slot 1 (Large)</option>
                                <option value="2">Slot 2 (Small Top)</option>
                                <option value="3">Slot 3 (Small Bottom)</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center gap-4 bg-slate-50 rounded-xl p-1.5 border border-slate-200/60">
                            <button type="button" @click="prevMonth()" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-white rounded-lg transition-all shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg></button>
                            <span class="w-32 text-center text-sm font-black text-slate-700 tracking-wide" x-text="monthName + ' ' + currentYear"></span>
                            <button type="button" @click="nextMonth()" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-white rounded-lg transition-all shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></button>
                        </div>
                    </div>
                    
                    <!-- Calendar Grid -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                        <!-- Days Header -->
                        <div class="grid bg-slate-50 border-b border-slate-200" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                            <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']">
                                <div class="py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider" x-text="day"></div>
                            </template>
                        </div>
                        <!-- Days Grid -->
                        <div class="grid bg-white" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                            <template x-for="(day, index) in calendarDays" :key="index">
                                <div class="border-b border-r border-slate-100 p-2 relative group hover:bg-slate-50/50 transition-colors"
                                     style="min-height: 75px;"
                                     :class="{ 'border-r-0': (index + 1) % 7 === 0, 'bg-slate-50/30': isPastDay(day) }">
                                    <template x-if="day">
                                        <div class="w-full h-full flex flex-col">
                                            <span class="text-sm font-bold text-slate-700 mb-1" x-text="day" :class="{ 'text-blue-600': isToday(day) }"></span>
                                            
                                            <!-- Status Indicator -->
                                            <div class="mt-auto">
                                                <template x-if="isDayBooked(day)">
                                                    <div class="w-full bg-red-100 text-red-700 font-bold py-1 px-0.5 sm:px-1.5 rounded uppercase tracking-wider text-center border border-red-200" style="font-size: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Booked">
                                                        <span class="hidden sm:inline">Booked</span>
                                                        <span class="sm:hidden">B</span>
                                                    </div>
                                                </template>
                                                <template x-if="!isDayBooked(day) && !isPastDay(day)">
                                                    <div class="w-full bg-emerald-50 text-emerald-600 font-bold py-1 px-0.5 sm:px-1.5 rounded uppercase tracking-wider text-center border border-emerald-200/60 opacity-0 group-hover:opacity-100 transition-opacity" style="font-size: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Available">
                                                        <span class="hidden sm:inline">Available</span>
                                                        <span class="sm:hidden">A</span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    window.AdminBanners = @json($banners);
    
    document.addEventListener('alpine:init', () => {
        Alpine.data('bannerManager', () => ({
            filterSlot: 'all',
            filterStatus: 'all',
            createSlot: '1',
            createPreview: '',
            createStartsAt: '',
            createEndsAt: '',
            
            isEditModalOpen: false,
            editBanner: {},
            editStartInstance: null,
            editEndInstance: null,
            
            isCalendarModalOpen: false,
            calendarSlot: '1',
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            allBanners: window.AdminBanners || [],
            
            init() {
                // Initialize the flatpickr for the create form
                flatpickr(this.$refs.createStartCalendar, {
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: "Y-m-d H:i",
                    minDate: "today",
                    appendTo: document.body,
                });
                flatpickr(this.$refs.createEndCalendar, {
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: "Y-m-d H:i",
                    minDate: "today",
                    appendTo: document.body,
                });
            },
            
            openEditModal(bannerData) {
                this.editBanner = bannerData;
                this.isEditModalOpen = true;
                this.$nextTick(() => {
                    this.initEditCalendar();
                });
            },
            
            initEditCalendar() {
                if(this.editStartInstance) this.editStartInstance.destroy();
                if(this.editEndInstance) this.editEndInstance.destroy();
                
                this.editStartInstance = flatpickr(this.$refs.editStartCalendar, {
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: "Y-m-d H:i",
                    defaultDate: this.editBanner.starts_at
                });
                
                this.editEndInstance = flatpickr(this.$refs.editEndCalendar, {
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: "Y-m-d H:i",
                    defaultDate: this.editBanner.ends_at
                });
            },
            
            handleCreateImagePreview(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.createPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            
            showRow(slot, status) {
                const slotMatches = this.filterSlot === 'all' || parseInt(this.filterSlot) === slot;
                const statusMatches = this.filterStatus === 'all' || this.filterStatus === status;
                return slotMatches && statusMatches;
            },
            
            openCalendarModal() {
                this.isCalendarModalOpen = true;
            },
            
            closeCalendarModal() {
                this.isCalendarModalOpen = false;
            },
            
            nextMonth() {
                if (this.currentMonth === 11) {
                    this.currentMonth = 0;
                    this.currentYear++;
                } else {
                    this.currentMonth++;
                }
            },
            
            prevMonth() {
                if (this.currentMonth === 0) {
                    this.currentMonth = 11;
                    this.currentYear--;
                } else {
                    this.currentMonth--;
                }
            },
            
            get monthName() {
                return new Date(this.currentYear, this.currentMonth).toLocaleString('default', { month: 'long' });
            },
            
            get daysInMonth() {
                return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            },
            
            get monthStartDay() {
                return new Date(this.currentYear, this.currentMonth, 1).getDay();
            },
            
            get calendarDays() {
                let days = [];
                for(let i=0; i < this.monthStartDay; i++) {
                    days.push(null);
                }
                for(let i=1; i <= this.daysInMonth; i++) {
                    days.push(i);
                }
                return days;
            },
            
            isToday(day) {
                let today = new Date();
                return day === today.getDate() && this.currentMonth === today.getMonth() && this.currentYear === today.getFullYear();
            },
            
            isPastDay(day) {
                if(!day) return true;
                let today = new Date();
                today.setHours(0,0,0,0);
                let thisDay = new Date(this.currentYear, this.currentMonth, day);
                return thisDay < today;
            },
            
            isDayBooked(day) {
                if(!day) return false;
                let m = String(this.currentMonth + 1).padStart(2, '0');
                let d = String(day).padStart(2, '0');
                // Create strings compatible with Date constructor natively
                let dateStr = `${this.currentYear}-${m}-${d}`;
                let dayStart = new Date(`${dateStr}T00:00:00`);
                let dayEnd = new Date(`${dateStr}T23:59:59`);
                
                return this.allBanners.some(b => {
                    if (parseInt(b.position) !== parseInt(this.calendarSlot)) return false;
                    if (!b.is_active) return false;
                    
                    // b.starts_at might be YYYY-MM-DD HH:mm:ss instead of standard ISO
                    // Laravel dates usually come as "2026-05-28 02:00:00"
                    let bStart = new Date(b.starts_at.replace(' ', 'T'));
                    let bEnd = new Date(b.ends_at.replace(' ', 'T'));
                    
                    return bStart <= dayEnd && bEnd >= dayStart;
                });
            }
        }));
    });
</script>
@endsection
