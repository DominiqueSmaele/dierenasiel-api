<?php

namespace App\Http\Livewire\Shelter\Timeslot\Concerns;

use App\Models\Timeslot;
use Carbon\Carbon;
use Illuminate\Validation\Validator;

trait ValidatesTimeslot
{
    public $timeZone = 'Europe/Brussels';

    public function mountValidatesTimeslot() : void
    {
        $this->timeslot ??= Timeslot::make(['date' => $this->date]);
    }

    public function bootedValidatesTimeslot() : void
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }
            });
        });
    }

    protected function rules() : array
    {
        $maxDate = now($this->timeZone)->lastOfMonth()->endOfWeek(Carbon::SUNDAY)->addWeeks(2)->format('Y-m-d');

        return [
            'timeslot.date' => [
                'required',
                'date',
                'after_or_equal:' . now($this->timeZone)->format('Y-m-d'),
                'before_or_equal:' . $maxDate,
            ],
            'timeslot.start_time' => [
                'required',
                'date',
            ],
            'timeslot.end_time' => [
                'required',
                'date',
                'after:timeslot.start_time',
            ],
        ];
    }
}
