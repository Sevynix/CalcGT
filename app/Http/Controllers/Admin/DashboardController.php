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
        $startDateParam = $request->query('start_date');
        $endDateParam = $request->query('end_date');
        $filter = $request->query('filter', 'all');

        $isCustomRange = false;
        if ($startDateParam && $endDateParam) {
            $isCustomRange = true;
            $filterStartDate = Carbon::parse($startDateParam)->startOfDay()->toDateString();
            $filterEndDate = Carbon::parse($endDateParam)->endOfDay()->toDateString();
            $filter = 'custom';
        }

        $today = Carbon::today()->toDateString();
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();

        // Page Views
        $totalViews = PageView::sum('views');
        $todayViews = PageView::where('date', $today)->sum('views');
        $weekViews = PageView::where('date', '>=', $startOfWeek)->sum('views');
        $monthViews = PageView::where('date', '>=', $startOfMonth)->sum('views');
        
        $customViews = 0;
        if ($isCustomRange) {
            $customViews = PageView::whereBetween('date', [$filterStartDate, $filterEndDate])->sum('views');
        }

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
        $query = PageView::select('route', DB::raw('SUM(views) as total_views'))
            ->groupBy('route');
            
        if ($filter === 'day') {
            $query->where('date', $today);
        } elseif ($filter === 'week') {
            $query->where('date', '>=', $startOfWeek);
        } elseif ($filter === 'month') {
            $query->where('date', '>=', $startOfMonth);
        } elseif ($filter === 'custom') {
            $query->whereBetween('date', [$filterStartDate, $filterEndDate]);
        }

        $pageViewsList = $query->orderByDesc('total_views')->paginate(10)->withQueryString();

        // Chart Data
        $chartDates = [];
        $chartViews = [];
        
        if ($isCustomRange) {
            $dailyViews = PageView::select('date', DB::raw('SUM(views) as total'))
                ->whereBetween('date', [$filterStartDate, $filterEndDate])
                ->groupBy('date')
                ->pluck('total', 'date');

            $currentDate = Carbon::parse($filterStartDate);
            $endDateObj = Carbon::parse($filterEndDate);
            
            // Limit chart size if the period is insanely large, just map the raw days. 
            // ChartJS handles many points well, but we cap at 90 days to avoid memory issues
            $daysDiff = $currentDate->diffInDays($endDateObj);
            if ($daysDiff > 90) {
                // If > 90 days, we could aggregate by week, but let's just show standard 30 days for now
                // as a fallback or simply iterate. We'll iterate but limit to 90
                $endDateObj = $currentDate->copy()->addDays(90);
            }

            while ($currentDate <= $endDateObj) {
                $dateStr = $currentDate->toDateString();
                $chartDates[] = $currentDate->format('M d');
                $chartViews[] = $dailyViews[$dateStr] ?? 0;
                $currentDate->addDay();
            }
        } else {
            // Default 14 days chart
            $startDate = Carbon::today()->subDays(13)->toDateString();
            $dailyViews = PageView::select('date', DB::raw('SUM(views) as total'))
                ->where('date', '>=', $startDate)
                ->groupBy('date')
                ->pluck('total', 'date');

            for ($i = 13; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i)->toDateString();
                $chartDates[] = Carbon::parse($date)->format('M d');
                $chartViews[] = $dailyViews[$date] ?? 0;
            }
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
            'customViews',
            'isCustomRange',
            'startDateParam',
            'endDateParam',
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

    public function exportTraffic(Request $request)
    {
        $startDateParam = $request->query('start_date');
        $endDateParam = $request->query('end_date');
        $filter = $request->query('filter', 'all');

        $query = PageView::query();

        if ($startDateParam && $endDateParam) {
            $filterStartDate = Carbon::parse($startDateParam)->startOfDay()->toDateString();
            $filterEndDate = Carbon::parse($endDateParam)->endOfDay()->toDateString();
            $query->whereBetween('date', [$filterStartDate, $filterEndDate]);
        } elseif ($filter === 'day') {
            $query->where('date', Carbon::today()->toDateString());
        } elseif ($filter === 'week') {
            $query->where('date', '>=', Carbon::now()->startOfWeek()->toDateString());
        } elseif ($filter === 'month') {
            $query->where('date', '>=', Carbon::now()->startOfMonth()->toDateString());
        }

        $pageViews = $query->orderBy('date', 'desc')->orderBy('views', 'desc')->get();

        $fileName = 'traffic_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        if ($startDateParam && $endDateParam) {
            $fileName = 'traffic_report_' . $startDateParam . '_to_' . $endDateParam . '.csv';
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'Route', 'Views'];

        $callback = function() use($pageViews, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($pageViews as $view) {
                fputcsv($file, [$view->date, $view->route, $view->views]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
