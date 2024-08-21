<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessTransferQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa a fila de transferências';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Processando a fila de transferências...');
        \Artisan::call('queue:work', ['--stop-when-empty' => true]);
        $this->info('Fila de transferências processada com sucesso.');
        return 0;
    }
}
