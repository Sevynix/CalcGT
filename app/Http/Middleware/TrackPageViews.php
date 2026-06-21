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
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        // Don't track admin routes, assets, or api
        if (!$request->is('admin*') && !$request->is('_debugbar*')) {
            $route = $request->path() === '/' ? 'home' : $request->path();
            $date = Carbon::today()->toDateString();
            
            try {
                $pageView = PageView::firstOrCreate(
                    ['route' => $route, 'date' => $date],
                    ['views' => 0]
                );
                $pageView->increment('views');
            } catch (\Exception $e) {
                // Ignore DB errors
            }
        }
    }
}
