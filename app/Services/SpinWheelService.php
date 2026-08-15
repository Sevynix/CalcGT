<?php

namespace App\Services;

use App\Models\Competition;

class SpinWheelService
{
    private function poolKey(Competition $competition): string
    {
        return "spin_pool_{$competition->id}";
    }

    private function selectedKey(Competition $competition): string
    {
        return "spin_selected_{$competition->id}";
    }

    private function isPoolInitialized(Competition $competition): bool
    {
        return session()->has($this->poolKey($competition));
    }

    /**
     * Initialize the spin pool into session from registrations.
     * Always call this before drawing.
     */
    public function initPool(Competition $competition): array
    {
        $pool = $competition->registrations()
            ->pluck('grow_id')
            ->shuffle()
            ->toArray();

        session([
            $this->poolKey($competition)     => $pool,
            $this->selectedKey($competition) => [],
        ]);

        return $pool;
    }

    /**
     * Get current pool from session.
     * Auto-inits from DB if session not yet set.
     */
    public function getPool(Competition $competition): array
    {
        if (!$this->isPoolInitialized($competition)) {
            return $this->initPool($competition);
        }

        return session($this->poolKey($competition), []);
    }

    /**
     * Get currently selected participants from session.
     */
    public function getSelected(Competition $competition): array
    {
        return session($this->selectedKey($competition), []);
    }

    /**
     * Draw one random participant from the pool.
     * Returns the drawn name, or null if pool is empty.
     */
    public function drawOne(Competition $competition): ?string
    {
        $pool = $this->getPool($competition);

        if (empty($pool)) {
            return null;
        }

        $index = array_rand($pool);
        $drawn = $pool[$index];

        // Remove from pool
        array_splice($pool, $index, 1);
        session([$this->poolKey($competition) => $pool]);

        // Add to selected
        $selected   = $this->getSelected($competition);
        $selected[] = $drawn;
        session([$this->selectedKey($competition) => $selected]);

        return $drawn;
    }

    /**
     * Draw one random participant WITHOUT removing it from pool or adding to selected.
     * Useful for confirming the draw first.
     */
    public function drawPending(Competition $competition): ?string
    {
        $pool = $this->getPool($competition);

        if (empty($pool)) {
            return null;
        }

        $index = array_rand($pool);
        return $pool[$index];
    }

    /**
     * Accept a drawn participant, removing from pool and adding to selected.
     */
    public function acceptDraw(Competition $competition, string $name): void
    {
        $pool = $this->getPool($competition);
        
        $index = array_search($name, $pool);
        if ($index !== false) {
            array_splice($pool, $index, 1);
            session([$this->poolKey($competition) => $pool]);
        }

        $selected = $this->getSelected($competition);
        if (!in_array($name, $selected)) {
            $selected[] = $name;
            session([$this->selectedKey($competition) => $selected]);
        }
    }

    /**
     * Add a participant manually to the selected list.
     */
    public function addManual(Competition $competition, string $name): void
    {
        $selected = $this->getSelected($competition);
        if (!in_array($name, $selected)) {
            $selected[] = $name;
            session([$this->selectedKey($competition) => $selected]);
        }
    }

    /**
     * Remove a participant from the selected list.
     * If they were originally in the pool (they are a registration), return them to the pool.
     */
    public function removeSelected(Competition $competition, string $name): void
    {
        $selected = $this->getSelected($competition);
        $index = array_search($name, $selected);
        
        if ($index !== false) {
            array_splice($selected, $index, 1);
            session([$this->selectedKey($competition) => $selected]);
            
            // Check if they are a registered user, if so, put back in pool
            $isRegistered = $competition->registrations()->where('grow_id', $name)->exists();
            if ($isRegistered) {
                $pool = $this->getPool($competition);
                if (!in_array($name, $pool)) {
                    $pool[] = $name;
                    session([$this->poolKey($competition) => $pool]);
                }
            }
        }
    }

    /**
     * Draw all remaining until slot_total is filled.
     */
    public function drawAll(Competition $competition): array
    {
        $needed = $competition->effectiveSlotCount() - count($this->getSelected($competition));

        for ($i = 0; $i < $needed; $i++) {
            if ($this->drawOne($competition) === null) {
                break; // pool empty, stop
            }
        }

        return $this->getSelected($competition);
    }

    /**
     * Get remaining pool count.
     */
    public function getPoolCount(Competition $competition): int
    {
        return count($this->getPool($competition));
    }

    /**
     * Check if selected count has reached slot_count.
     */
    public function isSlotFull(Competition $competition): bool
    {
        return count($this->getSelected($competition)) >= $competition->effectiveSlotCount();
    }

    /**
     * Clear session data (call after bracket is generated).
     */
    public function clearSession(Competition $competition): void
    {
        session()->forget([
            $this->poolKey($competition),
            $this->selectedKey($competition),
        ]);
    }
}
