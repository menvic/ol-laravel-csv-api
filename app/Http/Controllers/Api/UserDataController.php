<?php

namespace App\Http\Controllers\Api;

use App\Jobs\CsvToUserDataJob;
use App\Services\UserDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserDataRequest;
use App\Http\Requests\UploadUserDataRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @group User Data
 */
class UserDataController extends Controller
{
    public function __construct(private UserDataService $userDataService)
    {
    }

    /**
     * Upload Data with CSV file.
     *
     * @bodyParam file file required The CSV file to upload. Example: ./storage/app/csv/example.csv
     *
     * @response 202 {"success": true, "message": "The file has been successfully uploaded and is being processed.", "data": {"name": "random_name_123456.csv"}}
     * @response 422 {"success": false, "message": "Validation errors", "data": {"file": ["The file field is required."]}}
     * @response 500 {"success": false, "message": "Failed to upload the file. Please try again later."}
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(UploadUserDataRequest $request): JsonResponse
    {
        try {
            $file = $request->file('file');

            if (!$file) {
                throw new \Exception('No file uploaded.');
            }

            $name = $file->hashName();

            $path = $file->storeAs('csv/queue', $name);

            CsvToUserDataJob::dispatch($path);

            return response()->json([
                'success'   => true,
                'message' => 'The file has been successfully uploaded and is being processed.',
                'data' => [
                    'name' => $name,
                ],
            ], 202);
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());

            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Failed to upload the file. Please try again later.',
            ], 500));
        }
    }

    /**
     * Retrieve a list of user data.
     *
     * @response 200 {"success": true, "message": "Ok", "data": [[...],[...]]}
     * @response 422 {"success": false, "message": "Validation errors", "data": {"age": ["The age field must be at least 18."]}}
     * @response 500 {"success": false, "message": "Failed to upload the file. Please try again later."}
     *
     * @param \App\Http\Requests\UserDataRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UserDataRequest $request): JsonResponse
    {
        try {
            $data = $this->userDataService->getUserData($request);

            return response()->json([
                'success'   => true,
                'message' => 'Ok',
                'count' => count($data),
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Retrieve a list of user data failed: ' . $e->getMessage());

            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Failed to retrieve a list of user data. Please try again later.',
            ], 500));
        }
    }
}
