<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSheltersRequest;
use App\Http\Resources\ShelterResource;
use App\Models\Shelter;
use Illuminate\Http\JsonResponse;

class ShelterController extends Controller
{
    public function index(GetSheltersRequest $request) : JsonResponse
    {
        $shelters = Shelter::query()
            ->select('*')
            ->with('address.country')
            ->when($request->q, fn ($query) => $query->search($request->q))
            ->when($request->location(), fn ($query) => $query->orderByDistance($request->location()))
            ->orderBy('name')
            ->orderBy('id')
            ->cursorPaginate($request->integer('per_page'));

        return ShelterResource::collection($shelters)
            ->response()
            ->setStatusCode(200);
    }
}
