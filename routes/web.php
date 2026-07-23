<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

// Sitemap
Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => 'https://www.growtopiacalculator.com/',                    'changefreq' => 'daily',   'priority' => '1.0'],
        ['loc' => 'https://www.growtopiacalculator.com/surgery-tools',        'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/autoclave',            'changefreq' => 'weekly',  'priority' => '0.8'],
        ['loc' => 'https://www.growtopiacalculator.com/gala-tools',           'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/nanoforge',            'changefreq' => 'weekly',  'priority' => '0.8'],
        ['loc' => 'https://www.growtopiacalculator.com/fishin-pack',          'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/firefighter-pack',     'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/city-pack',            'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/farm-pack',            'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/paleontologist-kit',   'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/science-kit',          'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/boo-pack',             'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/crime-pack',           'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/ssp-pack',             'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/rsp-pack',             'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/tackle-box',           'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/painter-pack',         'changefreq' => 'weekly',  'priority' => '0.9'],
        ['loc' => 'https://www.growtopiacalculator.com/level-calculator',     'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => 'https://www.growtopiacalculator.com/backpack-calculator',  'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => 'https://www.growtopiacalculator.com/roles-calculator',     'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => 'https://www.growtopiacalculator.com/bbm-provider',         'changefreq' => 'weekly',  'priority' => '0.8'],
        ['loc' => 'https://www.growtopiacalculator.com/science-provider',     'changefreq' => 'weekly',  'priority' => '0.8'],
        ['loc' => 'https://www.growtopiacalculator.com/guild-level',          'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => 'https://www.growtopiacalculator.com/feedback',             'changefreq' => 'weekly',  'priority' => '0.7'],
        ['loc' => 'https://www.growtopiacalculator.com/about',                'changefreq' => 'monthly', 'priority' => '0.5'],
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
Route::get('/summerfest-pack', function () { return view('layouts.event.summerfest-pack'); })->name('summerfest-pack');
Route::get('/valentine-pack', function () { return view('layouts.event.valentine-pack'); })->name('valentine-pack');
Route::get('/stpatrick-pack', function () { return view('layouts.event.stpatrick-pack'); })->name('stpatrick-pack');
Route::get('/easter-pack', function () { return view('layouts.event.easter-pack'); })->name('easter-pack');
Route::get('/cinco-pack', function () { return view('layouts.event.cinco-pack'); })->name('cinco-pack');
Route::get('/atm-calc', function () { return view('layouts.providers.atm-calc'); })->name('atm-calc');
Route::get('/shatter-crystal', function () { return view('layouts.carnival.shatter-crystal'); })->name('shatter-crystal');
Route::get('/awkward-unicorn', function () { return view('layouts.providers.awkward-unicorn'); })->name('awkward-unicorn');
Route::get('/updates', function () {
    $patchNotes = \App\Models\PatchNote::where('is_published', true)->orderBy('created_at', 'desc')->get();
    return view('updates', compact('patchNotes'));
})->name('updates');
Route::get('/about', function () { return view('about'); })->name('about');

// Public Feedback Routes
Route::get('/feedback', [\App\Http\Controllers\FeedbackController::class, 'index'])->name('feedback.index');
Route::get('/feedback/submit', [\App\Http\Controllers\FeedbackController::class, 'create'])->name('feedback.create');
Route::post('/feedback/submit', [\App\Http\Controllers\FeedbackController::class, 'store'])->middleware('throttle:3,5')->name('feedback.store');

// Public Community Opinions Routes
Route::get('/opinions', [\App\Http\Controllers\CommunityOpinionController::class, 'index'])->name('opinions.index');
Route::get('/opinions/{communityPost}', [\App\Http\Controllers\CommunityOpinionController::class, 'show'])->name('opinions.show');
Route::post('/opinions/{communityPost}/submit', [\App\Http\Controllers\CommunityOpinionController::class, 'store'])->middleware('throttle:3,5')->name('opinions.store');

// Admin Auth Routes
Route::get('/admin', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-traffic', [\App\Http\Controllers\Admin\DashboardController::class, 'exportTraffic'])->name('export-traffic');
    
    // Banners
    Route::get('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
    Route::post('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('banners.store');
    Route::put('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('banners.destroy');
    
    // Feedback
    Route::get('/feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('feedback.index');
    Route::put('/feedback/{feedback}', [\App\Http\Controllers\Admin\FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [\App\Http\Controllers\Admin\FeedbackController::class, 'destroy'])->name('feedback.destroy');
    
    // Patch Notes
    Route::resource('/patch-notes', \App\Http\Controllers\Admin\PatchNoteController::class)->except(['show']);

    // Community Opinions
    Route::resource('/community-posts', \App\Http\Controllers\Admin\CommunityPostController::class)->except(['show']);
    Route::get('/community-opinions', [\App\Http\Controllers\Admin\CommunityOpinionController::class, 'index'])->name('community-opinions.index');
    Route::put('/community-opinions/{communityOpinion}', [\App\Http\Controllers\Admin\CommunityOpinionController::class, 'update'])->name('community-opinions.update');
    Route::delete('/community-opinions/{communityOpinion}', [\App\Http\Controllers\Admin\CommunityOpinionController::class, 'destroy'])->name('community-opinions.destroy');

    // Settings
    Route::get('/settings/whats-new', [\App\Http\Controllers\Admin\SettingController::class, 'editWhatsNew'])->name('settings.whats-new');
    Route::post('/settings/whats-new', [\App\Http\Controllers\Admin\SettingController::class, 'updateWhatsNew'])->name('settings.whats-new.update');
});