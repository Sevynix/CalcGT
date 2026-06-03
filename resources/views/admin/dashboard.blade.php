@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome Back, Admin! 👋</h1>
            <p class="text-slate-500 font-medium mt-1">Here is a complete analysis of your site's performance.</p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Page Views Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all hover:-translate-y-1">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-brand-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Page Views (All Time)</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ number_format($totalViews) }}</h3>
                </div>
                <div class="w-12 h-12 bg-brand-100 rounded-2xl flex items-center justify-center text-brand-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
            </div>
            
            <div class="mt-4 flex flex-col gap-3 relative z-10 border-t border-slate-100 pt-4">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-semibold flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Today</span>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-700">{{ number_format($todayViews) }}</span>
                        <span class="flex items-center text-[10px] font-bold px-1.5 py-0.5 rounded-md {{ $todayTrend >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                            @if($todayTrend >= 0)
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg> +{{ $todayTrend }}%
                            @else
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg> {{ $todayTrend }}%
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-semibold flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>This Week</span>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-700">{{ number_format($weekViews) }}</span>
                        <span class="flex items-center text-[10px] font-bold px-1.5 py-0.5 rounded-md {{ $weekTrend >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                            @if($weekTrend >= 0)
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg> +{{ $weekTrend }}%
                            @else
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg> {{ $weekTrend }}%
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-semibold flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>This Month</span>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-700">{{ number_format($monthViews) }}</span>
                        <span class="flex items-center text-[10px] font-bold px-1.5 py-0.5 rounded-md {{ $monthTrend >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                            @if($monthTrend >= 0)
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg> +{{ $monthTrend }}%
                            @else
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg> {{ $monthTrend }}%
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Card -->
        <a href="{{ route('admin.feedback.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all hover:-translate-y-1 block">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Unread Feedback</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ number_format($unreadFeedback) }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-slate-500 relative z-10 border-t border-slate-100 pt-4 flex justify-between">
                <span>Total Feedback:</span>
                <span class="text-slate-700 font-bold">{{ number_format($totalFeedback) }}</span>
            </div>
        </a>

        <!-- Opinions Card -->
        <a href="{{ route('admin.community-opinions.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all hover:-translate-y-1 block">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Unread Opinions</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ number_format($unreadOpinions) }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-slate-500 relative z-10 border-t border-slate-100 pt-4 flex justify-between">
                <span>Total Opinions:</span>
                <span class="text-slate-700 font-bold">{{ number_format($totalOpinions) }}</span>
            </div>
        </a>

        <!-- Community Posts Card -->
        <a href="{{ route('admin.community-posts.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all hover:-translate-y-1 block">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Community Posts</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ number_format($totalCommunityPosts) }}</h3>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
            </div>
            <div class="mt-4 text-xs font-semibold text-slate-500 relative z-10 border-t border-slate-100 pt-4">
                Total Published Posts
            </div>
        </a>

    </div>

    <!-- Charts and Secondary Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- View Chart -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800 text-lg">Page Views (Last 14 Days)</h3>
            </div>
            <div class="relative h-[300px] w-full">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>

        <!-- Banners & Extra info -->
        <div class="flex flex-col gap-6">
            <!-- Banners Card -->
            <a href="{{ route('admin.banners.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all hover:-translate-y-1 block text-slate-900">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Active Ad Slots</p>
                        <h3 class="text-4xl font-black text-slate-900">{{ $activeBanners }} <span class="text-xl text-slate-400">/ {{ max(3, $totalBanners) }}</span></h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="mt-6 text-sm font-medium text-slate-500 relative z-10 flex items-center gap-2">
                    Manage Promotions <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>
            
            <!-- Patch Notes Card -->
            <a href="{{ route('admin.patch-notes.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all hover:-translate-y-1 block text-slate-900">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-rose-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Patch Notes</p>
                        <h3 class="text-4xl font-black text-slate-900">{{ number_format($totalPatchNotes) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-rose-100 rounded-2xl flex items-center justify-center text-rose-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                </div>
                <div class="mt-6 text-sm font-medium text-slate-500 relative z-10 flex items-center gap-2">
                    Published Updates
                </div>
            </a>
        </div>
    </div>

    <!-- Complete Page Views Table -->
    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden mt-6" id="page-views-analytics">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h3 class="font-bold text-slate-800 text-lg">Page Views Analytics</h3>
            <div class="flex items-center gap-2 bg-white rounded-lg p-1 border border-slate-200 shadow-sm text-sm">
                <a href="{{ route('admin.dashboard', ['filter' => 'day']) }}" class="px-3 py-1.5 rounded-md transition-colors {{ $filter === 'day' ? 'bg-brand-50 font-bold text-brand-700' : 'text-slate-500 hover:bg-slate-50' }}">Day</a>
                <a href="{{ route('admin.dashboard', ['filter' => 'week']) }}" class="px-3 py-1.5 rounded-md transition-colors {{ $filter === 'week' ? 'bg-brand-50 font-bold text-brand-700' : 'text-slate-500 hover:bg-slate-50' }}">Week</a>
                <a href="{{ route('admin.dashboard', ['filter' => 'month']) }}" class="px-3 py-1.5 rounded-md transition-colors {{ $filter === 'month' ? 'bg-brand-50 font-bold text-brand-700' : 'text-slate-500 hover:bg-slate-50' }}">Month</a>
                <a href="{{ route('admin.dashboard', ['filter' => 'all']) }}" class="px-3 py-1.5 rounded-md transition-colors {{ $filter === 'all' ? 'bg-brand-50 font-bold text-brand-700' : 'text-slate-500 hover:bg-slate-50' }}">All Time</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            @if($pageViewsList->count() > 0)
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Route / Tool</th>
                            <th class="px-6 py-4 text-right">Total Views</th>
                            <th class="px-6 py-4 text-center">Popularity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        @php 
                            // For pagination max views calculation might be localized to current page, 
                            // to make popularity bar scale correctly, let's find the max of the current collection.
                            $maxViews = $pageViewsList->max('total_views') ?: 1; 
                        @endphp
                        @foreach($pageViewsList as $page)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </div>
                                <span class="font-semibold text-slate-800">{{ $page->route }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="bg-brand-50 text-brand-700 py-1.5 px-3 rounded-lg font-bold">{{ number_format($page->total_views) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php $percentage = min(100, max(0, ($page->total_views / $maxViews) * 100)); @endphp
                                <div class="w-full bg-slate-100 rounded-full h-2.5">
                                    <div class="bg-brand-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-12 text-center text-slate-500 font-medium flex flex-col items-center">
                    <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    No page views recorded yet.
                </div>
            @endif
        </div>
        @if($pageViewsList->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $pageViewsList->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('viewsChart').getContext('2d');
        
        // Gradient for chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)'); // Brand color
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        const data = {
            labels: {!! json_encode($chartDates) !!},
            datasets: [{
                label: 'Page Views',
                data: {!! json_encode($chartViews) !!},
                borderColor: '#3b82f6', // Brand color
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#3b82f6',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 12 },
                            color: '#64748b'
                        }
                    },
                    y: {
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            font: { size: 12 },
                            color: '#64748b',
                            padding: 10,
                            callback: function(value) {
                                if (value >= 1000) return (value / 1000).toFixed(1) + 'k';
                                return value;
                            }
                        },
                        beginAtZero: true
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        };

        new Chart(ctx, config);
    });

    // Preserve scroll position across page reloads (for filters and pagination)
    document.addEventListener("DOMContentLoaded", function(event) { 
        var scrollpos = sessionStorage.getItem('dashboard_scrollpos');
        if (scrollpos) {
            window.scrollTo(0, scrollpos);
            sessionStorage.removeItem('dashboard_scrollpos');
        }
    });

    window.addEventListener('beforeunload', function(e) {
        sessionStorage.setItem('dashboard_scrollpos', window.scrollY);
    });
</script>
@endsection
