<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;
use App\Models\Transaction;
class TransactionDetailSeeder extends Seeder {
    public function run(): void {
        TransactionDetail::truncate();
        $transactions = Transaction::all();
        foreach($transactions as $transaction) {
            $details = TransactionDetail::factory()->count(rand(2,4))->create([
                'transaction_id' => $transaction->id
            ]);
            $transaction->update([
                'total_amount' => $details->sum('subtotal')
            ]);
        }
    }
}
