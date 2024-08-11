<?php

namespace App\Http\Livewire\Shelter\Animal;

use App\Http\Livewire\Shelter\Animal\Concerns\ValidatesAnimal;
use App\Models\Animal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use WireElements\Pro\Components\SlideOver\SlideOver;

class UpdateAnimalSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesAnimal,
        WithFileUploads;

    public Animal $animal;

    public TemporaryUploadedFile | string | null $image = null;

    public bool $withoutImage = false;

    public function mount(int $animalId) : void
    {
        $this->animal = Animal::find($animalId);
    }

    public function booted() : void
    {
        $this->authorize('update', $this->animal);
    }

    public function update() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->animal->save();

            if ($this->image === null) {
                return;
            }

            $this->animal->addMediaFromDisk($this->image->storagePath(), $this->image->storageDisk())
                ->setFileName(Str::ascii($this->image->getClientOriginalName()))
                ->toMediaCollection('image');

            $this->reset('image');
        });

        $this->close(andDispatch: [
            'animalUpdated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.animal.update-animal-slide-over');
    }
}
