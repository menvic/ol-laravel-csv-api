<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Csv\CsvToDatabaseService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CsvToUserDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $filePath)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(CsvToDatabaseService $csvToDatabaseService): void
    {
        $startRecordTime = microtime(true);

        Log::info('CsvToUserDataJob is processing with file path: ' . $this->filePath);

        $csvToDatabaseService->processCsvFile($this->filePath);

        $endRecordTime = microtime(true);
        $executionTime = round($endRecordTime - $startRecordTime, 2);

        $memoryUsage = memory_get_usage();
        $peakMemoryUsage = memory_get_peak_usage();

        Log::info("CsvToUserDataJob has completed successfully with file path: {$this->filePath} in {$executionTime} seconds, Memory usage: " . round($memoryUsage / 1024 / 1024, 2) . " MB (Peak: " . round($peakMemoryUsage / 1024 / 1024, 2) . " MB)");
    }
}
