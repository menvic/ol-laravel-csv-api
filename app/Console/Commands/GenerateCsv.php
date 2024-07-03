<?php

namespace App\Console\Commands;

use App\Services\Csv\CsvGeneratorService;
use Illuminate\Console\Command;

class GenerateCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:generate {rows=1000000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CSV file with random data';

    public function __construct(public CsvGeneratorService $csvGeneratorService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rows = (int) $this->argument('rows');

        $startRecordTime = microtime(true);

        $this->output->progressStart($rows);

        $fileName = $this->csvGeneratorService->generateFile($rows, [$this->output, 'progressAdvance']);

        $this->output->progressFinish();

        $endRecordTime = microtime(true);
        $executionTime = round($endRecordTime - $startRecordTime, 2);

        $memoryUsage = memory_get_usage();
        $peakMemoryUsage = memory_get_peak_usage();

        $this->info("Generated {$rows} records and saved to {$fileName} in {$executionTime} seconds at " . now()->toDateTimeString());
        $this->line("Memory usage: " . round($memoryUsage / 1024 / 1024, 2) . " MB (Peak: " . round($peakMemoryUsage / 1024 / 1024, 2) . " MB)");
    }
}
