<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShelterResource;
use App\Models\Shelter;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ShelterTimeslotController extends Controller
{
    public function index() : JsonResponse
    {
        $shelters = Shelter::query()
            ->select('*')
            ->whereHas('timeslots', function ($query) {
                $query->where('date', '>=', Carbon::today())
                    ->whereNull('user_id');
            })
            ->with(['timeslots' => function ($query) {
                $query->where('date', '>=', Carbon::today())
                    ->whereNull('user_id')
                    ->orderBy('date')
                    ->orderBy('start_time');
            }])
            ->orderBy('name')
            ->orderBy('id')
            ->get();

        return ShelterResource::collection($shelters)
            ->response()
            ->setStatusCode(200);
    }
}
