<?php

namespace App\Http\Livewire\Shelter\Admin;

use App\Enums\ShelterRole;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class AdminsOverviewPage extends Component
{
    use AuthorizesRequests,
        WithPagination;

    public Shelter $shelter;

    public function booted() : void
    {
        $this->authorize('viewAnyAdmin', [User::class, $this->shelter]);
    }

    public function render() : View
    {
        return view('livewire.shelter.admin.admins-overview-page', [
            'admins' => User::query()
                ->where('shelter_id', $this->shelter->id)
                ->whereHasRole(ShelterRole::admin)
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->orderBy('id')
                ->paginate(10),
        ])->layout('layouts.shelter', ['shelter' => $this->shelter]);
    }
}
