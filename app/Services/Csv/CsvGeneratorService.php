<?php

namespace App\Services\Csv;

use App\Models\UserData;

class CsvGeneratorService
{
    // Adjust the chunk size based on your system's capabilities
    const GENERATOR_CHUNK_SIZE = 1000;

    const STORAGE_PATH = 'app/csv';

    /**
     * Generate CSV file with random data.
     *
     * @param int $rows count of rows to generate
     * @param callable $progress
     * @return string  The filename of the generated CSV file
     */
    public function generateFile(int $rows, callable $progress): string
    {
        $fileName = 'user_data_' . now()->format('Ymd_His') . '.csv';
        $filePath = storage_path(self::STORAGE_PATH . '/' . $fileName);

        $file = fopen($filePath, 'w');
        flock($file, LOCK_SH);

        $userDataModel = new UserData();

        // Write CSV header
        fputcsv($file, $userDataModel->getFillable());

        $chunkSize = self::GENERATOR_CHUNK_SIZE;
        $batches = (int) ceil($rows / $chunkSize);
        $totalRows = 0;

        for ($i = 0; $i < $batches; $i++) {
            if ($i == $batches - 1) {
                $chunkSize = $rows - $totalRows;
            }

            // for ($i = 0; $i < $rows; $i++) {
            //     fputcsv($file, $this->generateData()->current());
            // }

            $records = $userDataModel->factory()->count($chunkSize)->make();
            foreach ($records as $row) {
                fputcsv($file, $row->toArray());
            }
            unset($records);

            $totalRows += $chunkSize;
            $progress($chunkSize);
        }

        flock($file, LOCK_UN);
        fclose($file);

        return $fileName;
    }

    // private function generateData(): Generator
    // {
    //     yield UserData::factory()->make();
    // }
}
