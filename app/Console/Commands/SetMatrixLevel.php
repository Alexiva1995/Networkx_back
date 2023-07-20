<?php

namespace App\Console\Commands;

use App\Services\MatrixService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SetMatrixLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matrix:set_level';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron para actualizar el nivel de matrix de los usuarios';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $matrix_service;
    public function __construct(MatrixService $matrixService)
    {
        $this->matrix_service = $matrixService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Cron Matrix - '.Carbon::now());
        $this->matrix_service->levelOne();
        Log::info('Cron Matrix endend - '.Carbon::now());
    }
}
