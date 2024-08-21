<?php

namespace App\Jobs;

use App\Models\ContaBancaria;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fromAccountId;
    protected $toAccountId;
    protected $amount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fromAccountId, $toAccountId, $amount)
    {
        $this->fromAccountId = $fromAccountId;
        $this->toAccountId = $toAccountId;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fromAccount = ContaBancaria::find($this->fromAccountId);
        $toAccount = ContaBancaria::find($this->toAccountId);

        if ($fromAccount && $toAccount && $fromAccount->saldo >= $this->amount) {
            $fromAccount->saldo -= $this->amount;
            $toAccount->saldo += $this->amount;

            $fromAccount->save();
            $toAccount->save();
        }
    }
}
