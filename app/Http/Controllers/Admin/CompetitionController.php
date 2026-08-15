<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Tournament;
use App\Services\Bracket\FixedBracketOptionsResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompetitionController extends Controller
{
    public function store(Request $request, Tournament $tournament)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'detail'           => 'nullable|string',
            'photo'            => 'nullable|image|max:2048',
            'reveal_order'     => 'required|integer|min:1',
            'slot_count'       => 'required|in:16,24,32',
            'has_bracket'      => 'boolean',
            'bracket_format'   => ['nullable', 'required_if:has_bracket,1', 'string', \Illuminate\Validation\Rule::in(\App\Services\Bracket\FixedBracketOptionsResolver::validCodes())],
            'has_registration' => 'boolean',
        ]);

        $slotCount = (int) $request->slot_count;

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('competitions', 'public');
        }

        $competition = Competition::create([
            'tournament_id'    => $tournament->id,
            'name'             => $request->name,
            'detail'           => $request->detail,
            'photo_path'       => $photoPath,
            'reveal_order'     => $request->reveal_order,
            'slot_total'       => $slotCount,
            'slot_count'       => $slotCount,
            'bracket_format'   => $request->boolean('has_bracket') ? $request->bracket_format : null,
            'has_registration' => $request->boolean('has_registration'),
            'has_bracket'      => $request->boolean('has_bracket'),
            'status'           => 'hidden',
        ]);

        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', "Lomba \"{$competition->name}\" berhasil dibuat.");
    }

    public function edit(Tournament $tournament, Competition $competition)
    {
        return view('admin.competitions.edit', compact('tournament', 'competition'));
    }

    public function update(Request $request, Tournament $tournament, Competition $competition)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'detail'           => 'nullable|string',
            'photo'            => 'nullable|image|max:2048',
            'reveal_order'     => 'required|integer|min:1',
            'slot_count'       => 'required|in:16,24,32',
            'has_bracket'      => 'boolean',
            'bracket_format'   => ['nullable', 'required_if:has_bracket,1', 'string', \Illuminate\Validation\Rule::in(\App\Services\Bracket\FixedBracketOptionsResolver::validCodes())],
            'has_registration' => 'boolean',
        ]);

        $slotCount = (int) $request->slot_count;

        $data = [
            'name'             => $request->name,
            'detail'           => $request->detail,
            'reveal_order'     => $request->reveal_order,
            'slot_total'       => $slotCount,
            'slot_count'       => $slotCount,
            'bracket_format'   => $request->boolean('has_bracket') ? $request->bracket_format : null,
            'has_registration' => $request->boolean('has_registration'),
            'has_bracket'      => $request->boolean('has_bracket'),
        ];

        if ($request->hasFile('photo')) {
            if ($competition->photo_path) {
                Storage::disk('public')->delete($competition->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('competitions', 'public');
        }

        $competition->update($data);

        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', "Lomba \"{$competition->name}\" diperbarui.");
    }

    public function destroy(Tournament $tournament, Competition $competition)
    {
        if ($competition->photo_path) {
            Storage::disk('public')->delete($competition->photo_path);
        }

        $competition->delete();
        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', 'Lomba dihapus.');
    }
}
