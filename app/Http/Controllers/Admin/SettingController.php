<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function editWhatsNew()
    {
        $setting = Setting::where('key', 'whats_new_modal')->first();
        
        $defaultData = [
            'is_enabled' => true,
            'version_key' => 'v1',
            'title_normal' => '',
            'title_highlight' => '',
            'description' => '',
            'features' => [],
            'buttons' => []
        ];

        $data = $setting ? json_decode($setting->value, true) : $defaultData;

        // Ensure features and buttons are arrays to prevent errors
        if (!isset($data['features']) || !is_array($data['features'])) {
            $data['features'] = $defaultData['features'];
        }
        if (!isset($data['buttons']) || !is_array($data['buttons'])) {
            $data['buttons'] = $defaultData['buttons'];
        }

        return view('admin.settings.whats-new', compact('data'));
    }

    public function updateWhatsNew(Request $request)
    {
        $request->validate([
            'version_key' => 'required|string|max:50',
            'title_normal' => 'required|string|max:255',
            'title_highlight' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'features' => 'nullable|array',
            'features.*.title' => 'required|string|max:255',
            'features.*.description' => 'required|string|max:500',
            'features.*.badge' => 'nullable|string|max:50',
            'buttons' => 'nullable|array',
            'buttons.*.text' => 'required|string|max:255',
            'buttons.*.url' => 'required|string|max:500',
        ]);

        $data = [
            'is_enabled' => $request->has('is_enabled'),
            'version_key' => $request->version_key,
            'title_normal' => $request->title_normal,
            'title_highlight' => $request->title_highlight,
            'description' => $request->description,
            'features' => $request->input('features', []),
            'buttons' => $request->input('buttons', []),
        ];

        // Ensure features have a default icon bg/stroke since we're keeping it simple for admin
        foreach ($data['features'] as &$feature) {
            $feature['icon_bg'] = 'bg-gradient-to-br from-[#edf4fc] to-[#dbe8f9]';
            $feature['icon_stroke'] = '#4b84e0';
        }
        
        // Ensure buttons have a default bg since we're making it dynamic
        foreach ($data['buttons'] as $index => &$button) {
            if ($index % 2 == 0) {
                $button['bg_from'] = '#4b84e0';
                $button['bg_to'] = '#0e59c3';
            } else {
                $button['bg_from'] = '#6f9fe6';
                $button['bg_to'] = '#4b84e0';
            }
        }

        Setting::updateOrCreate(
            ['key' => 'whats_new_modal'],
            ['value' => json_encode($data)]
        );

        return redirect()->route('admin.settings.whats-new')->with('success', 'What\'s New modal settings updated successfully.');
    }
}
