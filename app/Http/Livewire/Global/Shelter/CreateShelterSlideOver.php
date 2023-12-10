<?php

namespace App\Http\Livewire\Global\Shelter;

use App\Http\Livewire\Global\Shelter\Concerns\ValidatesShelter;
use App\Models\Address;
use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateShelterSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesShelter;

    public Shelter $shelter;

    public Address $address;

    public function boot() : void
    {
        $this->authorize('create', Shelter::class);
    }

    public function create() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->address->save();

            $this->shelter->address()->associate($this->address);

            $this->shelter->save();
        });

        $this->close(andDispatch: [
            'shelterCreated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.global.shelter.create-shelter-slide-over');
    }
}
