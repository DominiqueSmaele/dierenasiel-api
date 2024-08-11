<?php

namespace App\Http\Livewire\Shelter\Animal;

use App\Http\Livewire\Shelter\Animal\Concerns\ValidatesAnimal;
use App\Models\Animal;
use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateAnimalSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesAnimal,
        WithFileUploads;

    public Animal $animal;

    public Shelter $shelter;

    public TemporaryUploadedFile | string | null $image = null;

    public function mount(int $shelterId) : void
    {
        $this->shelter = Shelter::find($shelterId);
    }

    public function booted() : void
    {
        $this->authorize('create', [Animal::class, $this->shelter]);
    }

    public function create() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->animal->shelter()->associate($this->shelter);
            $this->animal->save();

            $this->animal->qualities()->syncWithoutDetaching(
                $this->animal->type->qualities->sortBy('id')->values()->pluck('id')->toArray()
            );

            if ($this->image === null) {
                return;
            }

            $this->animal->addMediaFromDisk($this->image->storagePath(), $this->image->storageDisk())
                ->setFileName(Str::ascii($this->image->getClientOriginalName()))
                ->toMediaCollection('image');

            $this->reset('image');
        });

        $this->close(andDispatch: [
            'animalCreated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.animal.create-animal-slide-over');
    }
}
