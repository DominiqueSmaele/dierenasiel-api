<?php

namespace App\Http\Livewire\Global\Developer;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class DevelopersOverviewPage extends Component
{
    use AuthorizesRequests,
        WithPagination;

    public function booted() : void
    {
        $this->authorize('viewAnyDeveloper', User::class);
    }

    public function render() : View
    {
        return view('livewire.global.developer.developers-overview-page', [
            'developers' => User::query()
                ->whereHasRole(Role::developer)
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->orderBy('id')
                ->paginate(10),
        ])->layout('layouts.global');
    }
}
