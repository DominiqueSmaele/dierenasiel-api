<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetShelterAnimalsRequest;
use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use App\Models\Shelter;
use Illuminate\Http\JsonResponse;

class ShelterAnimalController extends Controller
{
    public function index(Shelter $shelter, GetShelterAnimalsRequest $request) : JsonResponse
    {
        $animals = Animal::query()
            ->select('*')
            ->where('shelter_id', $shelter->id)
            ->with('type', 'shelter.address.country', 'shelter.openingPeriods', 'qualities')
            ->when($request->q, fn ($query) => $query->search($request->q))
            ->orderBy('name')
            ->orderBy('id')
            ->cursorPaginate($request->integer('per_page'));

        return AnimalResource::collection($animals)
            ->response()
            ->setStatusCode(200);
    }
}
