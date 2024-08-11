<?php

namespace App\Http\Livewire\Shelter\Animal;

use App\Http\Livewire\Shelter\Animal\Concerns\ValidatesAnimalQualities;
use App\Models\Animal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\SlideOver\SlideOver;

class UpdateAnimalQualitiesSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesAnimalQualities;

    public Animal $animal;

    public function mount(int $animalId) : void
    {
        $this->animal = Animal::find($animalId);
    }

    public function booted() : void
    {
        $this->authorize('updateQualities', [$this->animal]);
    }

    public function update() : void
    {
        $this->validate();

        DB::transaction(function () {
            foreach ($this->animalQualities as $animalQuality) {
                $this->animal->qualities()->syncWithoutDetaching([
                    $animalQuality->quality->id => [
                        'value' => $animalQuality->value,
                    ],
                ]);
            }
        });

        $this->close(andDispatch: [
            'animalQualitiesUpdated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.animal.update-animal-qualities-slide-over');
    }
}
