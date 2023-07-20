<?php

namespace App\Console\Commands;

use App\Services\PagueloFacilService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelPagueloFacilTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:paguelofacil:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancels pending transactions if their expiration_date has passed (15 minutes)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Log::info("start cancel:paguelofacil:transactions");

        $pagueloFacilService = resolve(PagueloFacilService::class);
        
        $pagueloFacilService->cancelTransactions();
        
        // Log::info("end cancel:paguelofacil:transactions");
    }
}
