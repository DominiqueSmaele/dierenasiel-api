<?php

namespace App\Http\Livewire\Global\Shelter;

use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class SheltersOverviewPage extends Component
{
    use AuthorizesRequests,
        WithPagination;

    protected $listeners = [
        'shelterCreated' => '$refresh',
        'shelterUpdated' => '$refresh',
        'shelterDeleted' => '$refresh',
    ];

    public function booted() : void
    {
        $this->authorize('viewAny', Shelter::class);
    }

    public function render() : View
    {
        return view('livewire.global.shelter.shelters-overview-page', [
            'shelters' => Shelter::query()
                ->with('address.country')
                ->orderBy('name')
                ->orderBy('id')
                ->paginate(20),
        ])->layout('layouts.global');
    }
}
