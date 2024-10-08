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

    public ?string $searchValue = null;

    #[Url(as: 'q')]
    public ?int $filterValue = null;

    protected $listeners = [
        'animalCreated' => '$refresh',
        'animalUpdated' => '$refresh',
        'animalDeleted' => '$refresh',
    ];

    public function booted() : void
    {
        $this->authorize('viewAny', [Animal::class, $this->shelter]);
    }

    public function updatingSearchValue()
    {
        $this->resetPage();
    }

    public function resetFilter() : void
    {
        $this->reset('filterValue');
    }

    public function render() : View
    {
        return view('livewire.shelter.animal.animals-overview-page', [
            'animals' => Animal::query()
                ->when($this->searchValue)->search($this->searchValue)
                ->when($this->filterValue)->where('type_id', $this->filterValue)
                ->where('shelter_id', $this->shelter->id)
                ->with('shelter')
                ->orderBy('name')
                ->orderBy('id')
                ->paginate(20),
        ])->layout('layouts.shelter', ['shelter' => $this->shelter]);
    }
}
