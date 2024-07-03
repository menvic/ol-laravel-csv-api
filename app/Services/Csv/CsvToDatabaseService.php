<?php

namespace App\Services\Csv;

use App\Models\UserData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CsvToDatabaseService
{
    public function processCsvFile(string $filePath): void
    {
        $storage = Storage::disk('local');

        if (!$storage->exists($filePath)) {
            Log::error('File does not exist: ' . $filePath);
            throw new \Exception('File does not exist: ' . $filePath);
        }

        $userDataModel = new UserData();

        $table = $userDataModel->getTable();
        $fillableFields = $userDataModel->getFillable();
        $fieldString = implode(', ', $fillableFields);

        $file = $storage->path($filePath);

        DB::beginTransaction();

        try {
            $query = sprintf("COPY %s(%s) FROM '%s' CSV HEADER", $table, $fieldString, $file);

            DB::statement($query);
            DB::commit();

            $storage->delete($filePath);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing CSV file({$filePath}): {$e->getMessage()}");
            throw $e;
        }
    }
}
