<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteTimeslotUserRequest;
use App\Http\Resources\TimeslotResource;
use App\Models\Timeslot;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TimeslotUserController extends Controller
{
    public function index() : JsonResponse
    {
        $timeslots = Timeslot::query()
            ->select('*')
            ->where('date', '>=', Carbon::today('Europe/Brussels'))
            ->where('user_id', auth()->user()->id)
            ->with('shelter')
            ->orderBy('date')
            ->orderBy('start_time')
            ->orderBy('id')
            ->get();

        return TimeslotResource::collection($timeslots)
            ->response()
            ->setStatusCode(200);
    }

    public function delete(DeleteTimeslotUserRequest $request) : Response
    {
        try {
            $timeslot = Timeslot::find($request->id);

            $date = Carbon::createFromFormat('d-m-Y', $timeslot->date)->startOfDay();

            if ($timeslot->user->id !== auth()->user()->id || $date->isPast()) {
                return response(['message' => __('api.unprocessable')], 422);
            }

            DB::transaction(function () use ($request, $timeslot) {
                $timeslot->user()->dissociate();
                $timeslot->save();
            });
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], 400);
        }

        return response(null)->setStatusCode(204);
    }
}
