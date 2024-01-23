<?php

namespace App\Http\Livewire\Shelter\Admin;

use App\Enums\ShelterRole;
use App\Http\Livewire\Shelter\Admin\Concerns\ValidatesAdmin;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateAdminSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesAdmin;

    public Shelter $shelter;

    public function mount(int $shelterId) : void
    {
        $this->shelter = Shelter::find($shelterId);
    }

    public function booted() : void
    {
        $this->authorize('createAdmin', [User::class, $this->shelter]);
    }

    public function create() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->user->shelter()->associate($this->shelter);
            $this->user->save();

            $this->user->syncRoles([ShelterRole::admin], $this->shelter);
        });

        $this->close(andDispatch: [
            'adminCreated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.admin.create-admin-slide-over');
    }
}
