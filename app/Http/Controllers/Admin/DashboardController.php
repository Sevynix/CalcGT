<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageView;
use App\Models\Feedback;
use App\Models\Banner;
use App\Models\CommunityPost;
use App\Models\CommunityOpinion;
use App\Models\PatchNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();

        // Page Views
        $totalViews = PageView::sum('views');
        $todayViews = PageView::where('date', $today)->sum('views');
        $weekViews = PageView::where('date', '>=', $startOfWeek)->sum('views');
        $monthViews = PageView::where('date', '>=', $startOfMonth)->sum('views');

        // Calculate Comparisons (Trends)
        $yesterday = Carbon::yesterday()->toDateString();
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek()->toDateString();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek()->toDateString();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $yesterdayViews = PageView::where('date', $yesterday)->sum('views');
        $lastWeekViews = PageView::whereBetween('date', [$startOfLastWeek, $endOfLastWeek])->sum('views');
        $lastMonthViews = PageView::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])->sum('views');

        $todayTrend = $this->calculateTrend($todayViews, $yesterdayViews);
        $weekTrend = $this->calculateTrend($weekViews, $lastWeekViews);
        $monthTrend = $this->calculateTrend($monthViews, $lastMonthViews);

        // Complete list of page views with filter and pagination
        $filter = $request->query('filter', 'all');
        $query = PageView::select('route', DB::raw('SUM(views) as total_views'))
            ->groupBy('route');
            
        if ($filter === 'day') {
            $query->where('date', $today);
        } elseif ($filter === 'week') {
            $query->where('date', '>=', $startOfWeek);
        } elseif ($filter === 'month') {
            $query->where('date', '>=', $startOfMonth);
        }

        $pageViewsList = $query->orderByDesc('total_views')->paginate(10)->withQueryString();

        // Chart Data (Last 14 days)
        $startDate = Carbon::today()->subDays(13)->toDateString();
        $dailyViews = PageView::select('date', DB::raw('SUM(views) as total'))
            ->where('date', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartDates = [];
        $chartViews = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $chartDates[] = Carbon::parse($date)->format('M d');
            $chartViews[] = $dailyViews[$date] ?? 0;
        }

        // Engagement Metrics
        $unreadFeedback = Feedback::where('is_read', false)->count();
        $totalFeedback = Feedback::count();

        $activeBanners = Banner::where('is_active', true)->count();
        $totalBanners = Banner::count();

        $totalCommunityPosts = CommunityPost::where('is_published', true)->count();
        $unreadOpinions = CommunityOpinion::where('is_read', false)->count();
        $totalOpinions = CommunityOpinion::count();

        $totalPatchNotes = PatchNote::where('is_published', true)->count();

        return view('admin.dashboard', compact(
            'totalViews',
            'todayViews',
            'weekViews',
            'monthViews',
            'todayTrend',
            'weekTrend',
            'monthTrend',
            'pageViewsList',
            'chartDates',
            'chartViews',
            'unreadFeedback',
            'totalFeedback',
            'activeBanners',
            'totalBanners',
            'totalCommunityPosts',
            'unreadOpinions',
            'totalOpinions',
            'totalPatchNotes',
            'filter'
        ));
    }

    private function calculateTrend($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }
}
