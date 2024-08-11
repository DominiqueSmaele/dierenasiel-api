<?php

namespace App\Http\Livewire\Global\Shelter;

use App\Http\Livewire\Global\Shelter\Concerns\ValidatesShelter;
use App\Models\Address;
use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use WireElements\Pro\Components\SlideOver\SlideOver;

class UpdateShelterSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesShelter,
        WithFileUploads;

    public Shelter $shelter;

    public Address $address;

    public TemporaryUploadedFile | string | null $image = null;

    public bool $withoutImage = false;

    public function mount(int $shelterId) : void
    {
        $this->shelter = Shelter::find($shelterId);
    }

    public function booted() : void
    {
        $this->authorize('update', $this->shelter);
    }

    public function update() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->address->save();
            $this->shelter->save();

            if ($this->image === null) {
                return;
            }

            $this->shelter->addMediaFromDisk($this->image->storagePath(), $this->image->storageDisk())
                ->setFileName(Str::ascii($this->image->getClientOriginalName()))
                ->toMediaCollection('image');

            $this->reset('image');
        });

        $this->close(andDispatch: [
            'shelterUpdated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.global.shelter.update-shelter-slide-over');
    }
}
