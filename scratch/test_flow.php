<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Competition;
use App\Models\BracketNode;
use App\Services\BracketService;

$competition = Competition::find(20);
if (!$competition) {
    echo "Competition 20 not found, getting latest.\n";
    $competition = Competition::latest()->first();
}

echo "Testing Competition ID: {$competition->id}\n";
$service = app(BracketService::class);

$latestPhase = $competition->latestPhase();
echo "Latest Phase ID: {$latestPhase->id}, Type: {$latestPhase->bracket_type}, Status: {$latestPhase->status}\n";

$nodes = BracketNode::where('phase_id', $latestPhase->id)->get();
echo "Node Count: {$nodes->count()}\n";
foreach ($nodes as $n) {
    echo "Node ID {$n->id} [{$n->bracket_side} R{$n->round} P{$n->position}]: A='{$n->participant_a}', B='{$n->participant_b}', Winner='{$n->winner}', Locked=" . ($n->locked ? 'yes' : 'no') . "\n";
}
