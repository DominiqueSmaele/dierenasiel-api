<?php

namespace App\Http\Livewire\Shelter\Animal;

use App\Models\Animal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\Modal\Modal;

class DeleteAnimalModal extends Modal
{
    use AuthorizesRequests;

    public Animal $animal;

    public function mount(int $animalId) : void
    {
        $this->animal = Animal::find($animalId);
    }

    public function booted() : void
    {
        $this->authorize('delete', $this->animal);
    }

    public function delete() : void
    {
        DB::transaction(function () {
            $this->animal->delete();
        });

        $this->close(withForce: true, andDispatch: [
            'animalDeleted',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.animal.delete-animal-modal');
    }
}
