<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAnimalsRequest;
use App\Http\Resources\AnimalResource;
use App\Models\Animal;
use Illuminate\Http\JsonResponse;

class AnimalController extends Controller
{
    public function index(GetAnimalsRequest $request) : JsonResponse
    {
        $animals = Animal::query()
            ->select('*')
            ->with('type', 'shelter')
            ->when($request->q, fn ($query) => $query->search($request->q))
            ->orderBy('name')
            ->orderBy('id')
            ->cursorPaginate($request->integer('per_page'));

        return AnimalResource::collection($animals)
            ->response()
            ->setStatusCode(200);
    }
}
