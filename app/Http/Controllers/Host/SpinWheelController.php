<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Services\SpinWheelService;
use App\Services\BracketService;
use Illuminate\Http\Request;

class SpinWheelController extends Controller
{
    public function __construct(
        private SpinWheelService $spinService,
        private BracketService $bracketService
    ) {}

    public function show(Request $request, Competition $competition)
    {

        if (!in_array($competition->status, ['registration_closed', 'ongoing'])) {
            return back()->with('error', 'Spin wheel hanya tersedia setelah pendaftaran ditutup.');
        }

        // Always ensure pool is in session (auto-init if first time)
        $pool     = $this->spinService->getPool($competition);
        $selected = $this->spinService->getSelected($competition);
        $isFull   = $this->spinService->isSlotFull($competition);

        return view('host.spin', compact('competition', 'pool', 'selected', 'isFull'));
    }

    /**
     * AJAX: Draw one participant from pool WITHOUT committing.
     */
    public function spin(Request $request, Competition $competition)
    {
        if ($this->spinService->isSlotFull($competition)) {
            return response()->json(['error' => 'Slot sudah penuh.'], 422);
        }

        $drawn = $this->spinService->drawPending($competition);

        if ($drawn === null) {
            return response()->json(['error' => 'Pool kosong, tidak ada peserta tersisa.'], 422);
        }

        return response()->json([
            'drawn'      => $drawn,
        ]);
    }

    /**
     * AJAX: Accept the drawn participant.
     */
    public function acceptSpin(Request $request, Competition $competition)
    {
        $name = $request->input('name');
        if (!$name) return response()->json(['error' => 'Nama tidak valid.'], 422);

        $this->spinService->acceptDraw($competition, $name);

        return response()->json([
            'pool'       => $this->spinService->getPool($competition),
            'selected'   => $this->spinService->getSelected($competition),
            'pool_count' => $this->spinService->getPoolCount($competition),
            'is_full'    => $this->spinService->isSlotFull($competition),
        ]);
    }

    /**
     * AJAX: Add manual participant.
     */
    public function addManual(Request $request, Competition $competition)
    {
        if ($this->spinService->isSlotFull($competition)) {
            return response()->json(['error' => 'Slot sudah penuh.'], 422);
        }

        $name = trim($request->input('name'));
        if (!$name) return response()->json(['error' => 'Nama tidak valid.'], 422);

        $this->spinService->addManual($competition, $name);

        return response()->json([
            'pool'       => $this->spinService->getPool($competition),
            'selected'   => $this->spinService->getSelected($competition),
            'pool_count' => $this->spinService->getPoolCount($competition),
            'is_full'    => $this->spinService->isSlotFull($competition),
        ]);
    }

    /**
     * AJAX: Remove selected participant.
     */
    public function removeSelected(Request $request, Competition $competition)
    {
        $name = trim($request->input('name'));
        if (!$name) return response()->json(['error' => 'Nama tidak valid.'], 422);

        $this->spinService->removeSelected($competition, $name);

        return response()->json([
            'pool'       => $this->spinService->getPool($competition),
            'selected'   => $this->spinService->getSelected($competition),
            'pool_count' => $this->spinService->getPoolCount($competition),
            'is_full'    => $this->spinService->isSlotFull($competition),
        ]);
    }

    /**
     * AJAX: Draw all remaining to fill slot_total.
     */
    public function spinAll(Request $request, Competition $competition)
    {

        $selected = $this->spinService->drawAll($competition);

        return response()->json([
            'selected'   => $selected,
            'pool_count' => $this->spinService->getPoolCount($competition),
            'is_full'    => $this->spinService->isSlotFull($competition),
        ]);
    }

    /**
     * Reset spin session - re-init pool from registrations.
     */
    public function reset(Request $request, Competition $competition)
    {
        $this->spinService->initPool($competition);

        return redirect()->route('host.spin.show', $competition)
            ->with('success', 'Spin wheel direset. Pool diisi ulang dari daftar registrasi.');
    }

    /**
     * Generate bracket automatically from spin results.
     */
    public function generateBracket(Request $request, Competition $competition)
    {
        $selected = $this->spinService->getSelected($competition);
        
        if (count($selected) < 2) {
            return back()->with('error', 'Minimal 2 peserta untuk membuat bagan.');
        }

        if ($competition->has_bracket) {
            // Start Phase 1 (Generators will auto-seed and set status to ongoing)
            $phase = $this->bracketService->startFirstPhase($competition, $selected, 'auto');

            
            $msg = 'Bagan berhasil dibuat dan dikunci! Silakan kelola pertandingan.';
            $route = 'host.bracket';
        } else {
            $msg = 'Spin selesai! Lomba telah dimulai.';
            $route = 'host.timeline'; // Or whatever host page makes sense, timeline for now.
        }
        
        // Mark competition as ongoing
        $competition->update(['status' => 'ongoing']);
        
        // Clear session
        $this->spinService->clearSession($competition);

        return redirect()->route($route, $competition)
            ->with('success', $msg);
    }
}

