<?php

namespace App\Http\Livewire\Shelter\Animal;

use App\Models\Animal;
use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class AnimalsOverviewPage extends Component
{
    use AuthorizesRequests,
        WithPagination;

    public Shelter $shelter;

    public function booted() : void
    {
        $this->authorize('viewAny', [Animal::class, $this->shelter]);
    }

    public function render() : View
    {
        return view('livewire.shelter.animal.animals-overview-page', [
            'animals' => Animal::query()
                ->where('shelter_id', $this->shelter->id)
                ->with('type')
                ->orderBy('name')
                ->orderBy('id')
                ->paginate(12),
        ])->layout('layouts.shelter', ['shelter' => $this->shelter]);
    }
}
