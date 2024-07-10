<?php

namespace App\Http\Livewire\Shelter\Timeslot;

use App\Models\Timeslot;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\Modal\Modal;

class DeleteTimeslotVolunteerModal extends Modal
{
    use AuthorizesRequests;

    public Timeslot $timeslot;

    public function mount(int $timeslotId) : void
    {
        $this->timeslot = Timeslot::find($timeslotId);
    }

    public function booted() : void
    {
        $this->authorize('deleteVolunteer', $this->timeslot);
    }

    public function delete() : void
    {
        DB::transaction(function () {
            $this->timeslot->volunteer_id = null;

            $this->timeslot->save();
        });

        $this->close(withForce: true, andDispatch: [
            'timeslotVolunteerDeleted',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.timeslot.delete-timeslot-volunteer-modal');
    }
}
