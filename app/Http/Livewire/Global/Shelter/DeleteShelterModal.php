<?php

namespace App\Http\Livewire\Global\Shelter;

use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\Modal\Modal;

class DeleteShelterModal extends Modal
{
    use AuthorizesRequests;

    public Shelter $shelter;

    public function mount(int $shelterId) : void
    {
        $this->shelter = Shelter::find($shelterId);
    }

    public function booted() : void
    {
        $this->authorize('delete', $this->shelter);
    }

    public function delete() : void
    {
        DB::transaction(function () {
            $this->shelter->delete();

            $this->shelter->animals()->delete();
        });

        $this->close(withForce: true, andDispatch: [
            'shelterDeleted',
        ]);
    }

    public function render() : View
    {
        return view('livewire.global.shelter.delete-shelter-modal');
    }
}
