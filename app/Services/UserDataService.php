<?php

namespace App\Services;

use App\Models\UserData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\UserDataRequest;
use App\Http\Resources\UserDataResource;
use Illuminate\Database\Eloquent\Collection;

class UserDataService
{
    /**
     * Retrieves user data based on the provided request.
     *
     * @param UserDataRequest $request The request containing parameters for data fetching
     * @return array The user data as an associative array
     */
    public function getUserData(UserDataRequest $request): array
    {
        $requestFields = collect($request->all())->filter(function ($value, $key) use ($request) {
            return $request->filled($key);
        })->sortKeys()->all();

        $cacheKey = 'user_data_' . md5(json_encode($requestFields));
        $cache = Cache::store('redis');

        if ($cache->has($cacheKey)) {
            // return json_decode($cache->get($cacheKey), true);
        }

        $data = $this->fetchData($request);

        $dataResource = UserDataResource::collection($data);

        $cache->put($cacheKey, $dataResource->toJson(), now()->addMinutes(10));

        return $dataResource->resolve();
    }

    /**
     * Fetches user data based on the provided request.
     *
     * @param UserDataRequest $request The request containing parameters for data fetching
     * @return Collection The collection of user data after applying filters, sorting, and pagination
     */
    private function fetchData(UserDataRequest $request): Collection
    {
        $userDataModel = new UserData();
        $limit = $request->input('limit', 50);
        $offset = $request->input('offset', 0);
        $sortBy = $request->input('sort_by', 'id');
        $orderBy = $request->input('order_by', 'asc');

        $fillableFields = $userDataModel->getFillable();

        $query = $userDataModel->query();
        $query->select('id', ...$fillableFields);

        // filter
        $query->whereRaw('MATCH(first_name, last_name, age, gender, mobile_number, email, city, login, car_model, salary) AGAINST(?)', [$request->input('search')]);

        // sort and paginate
        $query->orderBy($sortBy, $orderBy)->orderBy('id', 'asc')
            ->skip($offset)
            ->take($limit);

        return $query->get();
    }
}
