<?php

namespace App\Console\Commands;

use App\Services\FutswapService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FutswapCanceled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'futswap:canceled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron para cancelar las ordenes de futswap en nuestra DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct(FutswapService $futswapService = null)
    {
        parent::__construct();
        $this->futswap = $futswapService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Log::info('Cron Futswap- '.Carbon::now());
        return $this->futswap->checkStatusFutswap();
    }
}
