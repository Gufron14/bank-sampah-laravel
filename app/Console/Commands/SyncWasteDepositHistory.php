<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WasteDeposit;
use App\Models\TransactionHistory;

class SyncWasteDepositHistory extends Command
{
    protected $signature = 'sync:waste-deposit-history';
    protected $description = 'Sync existing waste deposits to transaction history';

    public function handle()
    {
        $wasteDeposits = WasteDeposit::whereDoesntHave('transactionHistory')->get();

        foreach ($wasteDeposits as $wasteDeposit) {
            TransactionHistory::create([
                'user_id' => $wasteDeposit->user_id,
                'type' => TransactionHistory::TYPE_DEPOSIT,
                'amount' => $wasteDeposit->total_amount,
                'description' => 'Setor Sampah - ' . $wasteDeposit->total_weight . 'Kg',
                'reference_type' => WasteDeposit::class,
                'reference_id' => $wasteDeposit->id,
                'status' => 'completed',
                'created_at' => $wasteDeposit->created_at,
                'updated_at' => $wasteDeposit->updated_at,
            ]);
        }

        $this->info('Sync completed. Created ' . $wasteDeposits->count() . ' transaction histories.');
    }
}
