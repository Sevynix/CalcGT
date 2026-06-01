@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome Back, Admin! 👋</h1>
        <p class="text-slate-500 font-medium mt-1">Here is a quick overview of your site today.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Page Views Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-brand-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Total Page Views</p>
                    @php 
                        $totalViews = \App\Models\PageView::sum('views'); 
                        $todayViews = \App\Models\PageView::where('date', \Carbon\Carbon::today()->toDateString())->sum('views');
                        $weekViews = \App\Models\PageView::where('date', '>=', \Carbon\Carbon::now()->startOfWeek()->toDateString())->sum('views');
                    @endphp
                    <h3 class="text-4xl font-black text-slate-900">{{ number_format($totalViews) }}</h3>
                </div>
                <div class="w-12 h-12 bg-brand-100 rounded-2xl flex items-center justify-center text-brand-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
            </div>
            
            <div class="mt-4 flex flex-col gap-3 relative z-10 border-t border-slate-100 pt-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Today
                    </span>
                    <span class="font-bold text-slate-700 bg-slate-50 px-2.5 py-0.5 rounded-md border border-slate-100">{{ number_format($todayViews) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        This Week
                    </span>
                    <span class="font-bold text-slate-700 bg-slate-50 px-2.5 py-0.5 rounded-md border border-slate-100">{{ number_format($weekViews) }}</span>
                </div>
            </div>
        </div>

        <!-- Feedback Card -->
        <a href="{{ route('admin.feedback.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow block">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Unread Feedback</p>
                    @php $unreadCount = \App\Models\Feedback::where('is_read', false)->count(); @endphp
                    <h3 class="text-4xl font-black text-slate-900">{{ number_format($unreadCount) }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
            </div>
            
            <div class="mt-4 text-sm font-semibold text-slate-500 relative z-10">
                @if($unreadCount > 0)
                    <span class="text-amber-600">Requires attention</span>
                @else
                    <span class="text-emerald-600">All caught up!</span>
                @endif
            </div>
        </a>

        <!-- Banners Card -->
        <a href="{{ route('admin.banners.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow block">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Active Ad Slots</p>
                    @php $activeBanners = \App\Models\Banner::where('is_active', true)->count(); @endphp
                    <h3 class="text-4xl font-black text-slate-900">{{ $activeBanners }} <span class="text-xl text-slate-400">/ 3</span></h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            
            <div class="mt-4 text-sm font-semibold text-slate-500 relative z-10">
                Click to manage promos
            </div>
        </a>
    </div>

    <!-- Quick Stats Table -->
    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Most Popular Pages (All Time)</h3>
        </div>
        <div class="p-0">
            @php 
                $topPages = \App\Models\PageView::select('route', \DB::raw('SUM(views) as total_views'))
                    ->groupBy('route')
                    ->orderByDesc('total_views')
                    ->limit(5)
                    ->get();
            @endphp
            
            @if($topPages->count() > 0)
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Route / Tool</th>
                            <th class="px-6 py-3 text-right">Views</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        @foreach($topPages as $page)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">{{ $page->route }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="bg-slate-100 text-slate-700 py-1 px-2.5 rounded-lg font-bold">{{ number_format($page->total_views) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-8 text-center text-slate-500 font-medium">
                    No page views recorded yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
