<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('starts_at', 'asc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|integer|in:1,2,3',
            'title' => 'required|string|max:255',
            'link_url' => 'nullable|url',
            'image' => 'required|image|max:5120',
            'starts_at' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->position || !$request->ends_at) return;
                    $overlap = \App\Models\Banner::where('position', $request->position)
                        ->where(function ($q) use ($request, $value) {
                            $q->where('starts_at', '<', $request->ends_at)
                              ->where('ends_at', '>', $value);
                        })->exists();
                    if ($overlap) {
                        $fail('The selected dates overlap with another scheduled rental for this slot.');
                    }
                }
            ],
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'nullable|boolean'
        ]);

        $data = [
            'position' => $request->position,
            'title' => $request->title,
            'link_url' => $request->link_url,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->has('is_active')
        ];

        $path = $request->file('image')->store('banners', 'public');
        $data['image_path'] = 'storage/' . $path;

        Banner::create($data);

        return back()->with('success', 'New rental scheduled successfully.');
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'position' => 'required|integer|in:1,2,3',
            'title' => 'required|string|max:255',
            'link_url' => 'nullable|url',
            'image' => 'nullable|image|max:5120',
            'starts_at' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request, $banner) {
                    if (!$request->position || !$request->ends_at) return;
                    $overlap = \App\Models\Banner::where('position', $request->position)
                        ->where('id', '!=', $banner->id)
                        ->where(function ($q) use ($request, $value) {
                            $q->where('starts_at', '<', $request->ends_at)
                              ->where('ends_at', '>', $value);
                        })->exists();
                    if ($overlap) {
                        $fail('The selected dates overlap with another scheduled rental for this slot.');
                    }
                }
            ],
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'nullable|boolean'
        ]);

        $data = [
            'position' => $request->position,
            'title' => $request->title,
            'link_url' => $request->link_url,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('image')) {
            if ($banner->image_path && str_starts_with($banner->image_path, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $banner->image_path));
            }
            $path = $request->file('image')->store('banners', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        $banner->update($data);

        return back()->with('success', 'Rental updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path && str_starts_with($banner->image_path, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $banner->image_path));
        }
        $banner->delete();

        return back()->with('success', 'Rental deleted successfully.');
    }
}
