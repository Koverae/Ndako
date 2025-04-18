<?php

namespace Modules\App\Services\Wallet;

use App\Models\Team\Team;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Modules\App\Models\Kredit\Kredit;
use Modules\App\Models\Kredit\KreditTransaction;

class KreditService{

    public function charge(Team $team, int $amount, string $action, array $meta = []): bool
    {
        $kredit = Kredit::firstOrCreate(['team_id' => $team->id]);

        if ($kredit->balance < $amount) {
            // Optional: Trigger low balance event or notification
            return false;
        }

        $kredit->decrement('balance', $amount);

        KreditTransaction::create([
            'kredit_id' => $kredit->id,
            'team_id' => $team->id,
            // 'user_id' => $user->id,
            'type' => 'debit',
            'action' => $action,
            'amount' => $amount,
            'meta' => $meta,
        ]);

        return true;
    }

    public function topUp($teamId, int $amount, ?string $source = 'manual', array $meta = []): void
    {
        $kredit = Kredit::firstOrCreate(['team_id' => $teamId]);
        $kredit->increment('balance', $amount);

        KreditTransaction::create([
            'kredit_id' => $kredit->id,
            'team_id' => $teamId,
            // 'user_id' => $userId,
            'type' => 'credit',
            'action' => $source,
            'amount' => $amount,
            'meta' => $meta,
        ]);

        Log::info("Your wallet has been credited of {$amount} kredits");
    }

    public function getBalance(Team $team): int
    {
        return Kredit::firstOrCreate(['team_id' => $team->id])->balance;
    }

    // public function createWallet(Team $team, $kredits = 12){
    //     $kredit = Kredit::create([
    //         'team_id' => $team->id,
    //         'balance' => 0,
    //     ]);

    //     $kredit->
    // }
}
