<?php

namespace App\Http\Controllers;

use App\Http\Resources\TypeResource;
use App\Models\Type;
use Illuminate\Http\JsonResponse;

class TypeController extends Controller
{
    public function index() : JsonResponse
    {
        $types = Type::query()
            ->select('*')
            ->orderBy('id')
            ->get();

        return TypeResource::collection($types)
            ->response()
            ->setStatusCode(200);
    }
}
