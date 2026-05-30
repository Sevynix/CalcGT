<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

// Sitemap
Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => 'https://growtopiacalculator.com/',                    'changefreq' => 'daily',   'priority' => '1.0'],
        ['loc' => 'https://growtopiacalculator.com/surgery-tools',        'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/autoclave',            'changefreq' => 'weekly',  'priority' => '0.8'],
        ['loc' => 'https://growtopiacalculator.com/gala-tools',           'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/nanoforge',            'changefreq' => 'weekly',  'priority' => '0.8'],
        ['loc' => 'https://growtopiacalculator.com/fishin-pack',          'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/firefighter-pack',     'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/city-pack',            'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/farm-pack',            'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/paleontologist-kit',   'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/science-kit',          'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/boo-pack',             'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/crime-pack',           'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/ssp-pack',             'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/rsp-pack',             'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/tackle-box',           'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/painter-pack',         'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://growtopiacalculator.com/level-calculator',     'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => 'https://growtopiacalculator.com/backpack-calculator',  'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => 'https://growtopiacalculator.com/roles-calculator',     'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => 'https://growtopiacalculator.com/bbm-provider',         'changefreq' => 'weekly',  'priority' => '0.8'],
        ['loc' => 'https://growtopiacalculator.com/science-provider',     'changefreq' => 'weekly',  'priority' => '0.8'],
        ['loc' => 'https://growtopiacalculator.com/guild-level',          'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => 'https://growtopiacalculator.com/feedback',             'changefreq' => 'weekly',  'priority' => '0.7'],
        ['loc' => 'https://growtopiacalculator.com/about',                'changefreq' => 'monthly', 'priority' => '0.5'],
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $url) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>{$url['loc']}</loc>\n";
        $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
        $xml .= "    <priority>{$url['priority']}</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>';

    return response($xml, 200)
        ->header('Content-Type', 'application/xml; charset=utf-8')
        ->header('Cache-Control', 'public, max-age=3600');
});

// Public Tool Routes
Route::get('/', function () { 
    $now = now();
    $banners = \App\Models\Banner::where('is_active', true)
        ->where('starts_at', '<=', $now)
        ->where('ends_at', '>=', $now)
        ->get();
    return view('dashboard', compact('banners')); 
})->name('dashboard');
Route::get('/surgery-tools', function () { return view('layouts.surgery.msurg-packs'); })->name('surgery-tools');
Route::get('/autoclave', function () { return view('layouts.surgery.autoclave'); })->name('autoclave');
Route::get('/gala-tools', function () { return view('layouts.galactic.gala-tools'); })->name('gala-tools');
Route::get('/nanoforge', function () { return view('layouts.galactic.nanoforge'); })->name('nanoforge');
Route::get('/fishin-pack', function () { return view('layouts.fishing.fishin-pack'); })->name('fishin-pack');
Route::get('/firefighter-pack', function () { return view('layouts.firefighter.firefighter-pack'); })->name('firefighter-pack');
Route::get('/city-pack', function () { return view('layouts.city.city-pack'); })->name('city-pack');
Route::get('/farm-pack', function () { return view('layouts.farm.farm-pack'); })->name('farm-pack');
Route::get('/paleontologist-kit', function () { return view('layouts.paleontologist.paleontologist-kit'); })->name('paleontologist-kit');
Route::get('/science-kit', function () { return view('layouts.science.science-kit'); })->name('science-kit');
Route::get('/boo-pack', function () { return view('layouts.boo.boo-pack'); })->name('boo-pack');
Route::get('/crime-pack', function () { return view('layouts.crime.crime-pack'); })->name('crime-pack');
Route::get('/ssp-pack', function () { return view('layouts.ssp.ssp-pack'); })->name('ssp-pack');
Route::get('/rsp-pack', function () { return view('layouts.rsp.rsp-pack'); })->name('rsp-pack');
Route::get('/tackle-box', function () { return view('layouts.fishing.tackle-box'); })->name('tackle-box');
Route::get('/painter-pack', function () { return view('layouts.paint.painter-pack'); })->name('painter-pack');
Route::get('/bbm-provider', function () { return view('layouts.bbm.bbm-provider'); })->name('bbm-provider');
Route::get('/science-provider', function () { return view('layouts.science.science-provider'); })->name('science-provider');
Route::get('/level-calculator', function () { return view('layouts.player.level-calculator'); })->name('level-calculator');
Route::get('/backpack-calculator', function () { return view('layouts.player.backpack-calculator'); })->name('backpack-calculator');
Route::get('/roles-calculator', function () { return view('layouts.player.roles-calculator'); })->name('roles-calculator');
Route::get('/guild-level', function () { return view('layouts.guild.level-calculator'); })->name('guild-level');
Route::get('/about', function () { return view('about'); })->name('about');

// Public Feedback Routes
Route::get('/feedback', [\App\Http\Controllers\FeedbackController::class, 'index'])->name('feedback.index');
Route::get('/feedback/submit', [\App\Http\Controllers\FeedbackController::class, 'create'])->name('feedback.create');
Route::post('/feedback/submit', [\App\Http\Controllers\FeedbackController::class, 'store'])->middleware('throttle:3,5')->name('feedback.store');

// Admin Auth Routes
Route::get('/admin', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function() { return view('admin.dashboard'); })->name('dashboard');
    
    // Banners
    Route::get('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
    Route::post('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('banners.store');
    Route::put('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('banners.destroy');
    
    // Feedback
    Route::get('/feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedback.index');
    Route::put('/feedback/{feedback}', [\App\Http\Controllers\Admin\FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [\App\Http\Controllers\Admin\FeedbackController::class, 'destroy'])->name('feedback.destroy');
});