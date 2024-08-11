<?php

namespace App\Http\Livewire\Shelter\Timeslot;

use App\Http\Livewire\Shelter\Timeslot\Concerns\ValidatesTimeslot;
use App\Models\Timeslot;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\SlideOver\SlideOver;

class UpdateTimeslotSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesTimeslot;

    public Timeslot $timeslot;

    public function mount(int $timeslotId) : void
    {
        $this->timeslot = Timeslot::find($timeslotId);
    }

    public function booted() : void
    {
        $this->authorize('update', $this->timeslot);
    }

    public function update() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->timeslot->save();
        });

        $this->close(andDispatch: [
            'timeslotUpdated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.timeslot.update-timeslot-slide-over');
    }
}
