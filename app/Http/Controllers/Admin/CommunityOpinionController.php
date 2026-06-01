<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityOpinion;
use Illuminate\Http\Request;

class CommunityOpinionController extends Controller
{
    public function index(Request $request)
    {
        $query = CommunityOpinion::with('post')->orderBy('created_at', 'desc');
        
        if ($request->has('post_id')) {
            $query->where('community_post_id', $request->post_id);
        }
        
        $opinions = $query->get();
        return view('admin.community-opinions.index', compact('opinions'));
    }

    public function update(Request $request, CommunityOpinion $communityOpinion)
    {
        $communityOpinion->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Opinion marked as read.');
    }

    public function destroy(CommunityOpinion $communityOpinion)
    {
        $communityOpinion->delete();
        return redirect()->back()->with('success', 'Opinion deleted successfully.');
    }
}
