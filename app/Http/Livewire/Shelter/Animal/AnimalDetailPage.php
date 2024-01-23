<?php

namespace App\Http\Livewire\Shelter\Animal;

use App\Models\Animal;
use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class AnimalDetailPage extends Component
{
    use AuthorizesRequests,
        WithPagination;

    public Animal $animal;

    public Shelter $shelter;

    public function mount() : void
    {
        $this->shelter = $this->animal->shelter;
    }

    protected $listeners = [
        'animalUpdated' => '$refresh',
        'animalDeleted' => 'redirectToAnimalsOverviewPage',
    ];

    public function booted() : void
    {
        $this->authorize('view', $this->animal);
    }

    public function redirectToAnimalsOverviewPage()
    {
        return redirect(route('shelter.animals-overview', $this->shelter->id));
    }

    public function render() : View
    {
        return view('livewire.shelter.animal.animal-detail-page')
            ->layout('layouts.shelter', ['shelter' => $this->animal->shelter]);
    }
}
