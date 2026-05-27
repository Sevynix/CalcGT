<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageView;
use Carbon\Carbon;

class TrackPageViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't track admin routes, assets, or api
        if (!$request->is('admin*') && !$request->is('_debugbar*')) {
            $route = $request->path() === '/' ? 'home' : $request->path();
            $date = Carbon::today()->toDateString();
            
            try {
                PageView::updateOrCreate(
                    ['route' => $route, 'date' => $date],
                    ['views' => \DB::raw('views + 1')]
                );
            } catch (\Exception $e) {
                // Ignore DB errors so page still loads for users
            }
        }
        
        return $next($request);
    }
}
