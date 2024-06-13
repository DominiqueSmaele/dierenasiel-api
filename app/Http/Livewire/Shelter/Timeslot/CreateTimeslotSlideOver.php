<?php

namespace App\Http\Livewire\Shelter\Timeslot;

use App\Http\Livewire\Shelter\Timeslot\Concerns\ValidatesTimeslot;
use App\Models\Shelter;
use App\Models\Timeslot;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateTimeslotSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesTimeslot;

    public Timeslot $timeslot;

    public Shelter $shelter;

    public Carbon $date;

    public function mount(int $shelterId, string $dateString) : void
    {
        $this->shelter = Shelter::find($shelterId);
        $this->date = Carbon::parse($dateString);
    }

    public function booted() : void
    {
        $this->authorize('create', [Timeslot::class, $this->shelter]);
    }

    public function create() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->timeslot->shelter()->associate($this->shelter);
            $this->timeslot->save();
        });

        $this->close(andDispatch: [
            'timeslotCreated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.timeslot.create-timeslot-slide-over');
    }
}
