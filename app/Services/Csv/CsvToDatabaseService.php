<?php

namespace App\Services\Csv;

use App\Models\UserData;
use Illuminate\Support\Str;
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
        $fieldString = implode(',', $fillableFields);

        $file = $storage->path($filePath);
        $tempTable = 'temp_user_data_' . md5(Str::uuid());

        DB::beginTransaction();

        try {
            DB::statement("CREATE TEMPORARY TABLE {$tempTable} AS SELECT * FROM {$table} WHERE 1=0");

            $query = sprintf("COPY %s(%s) FROM '%s' CSV HEADER", $tempTable, $fieldString, $file);

            DB::statement($query);

            DB::statement("INSERT INTO {$table} ({$fieldString}) SELECT {$fieldString} FROM {$tempTable}");

            DB::commit();

            $storage->delete($filePath);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("Error processing CSV file({$filePath}): {$e->getMessage()}");

            throw $e;
        } finally {
            DB::statement("DROP TABLE IF EXISTS {$tempTable}");
        }
    }
}
