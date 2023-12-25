<?php

namespace App\Http\Livewire\Shelter;

use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class ShelterDetailPage extends Component
{
    use AuthorizesRequests;

    public Shelter $shelter;

    protected $listeners = [
        'shelterUpdated' => '$refresh',
    ];

    public function booted() : void
    {
        $this->authorize('view', $this->shelter);
    }

    public function render() : View
    {
        return view('livewire.shelter.shelter-detail-page')
            ->layout('layouts.shelter', ['shelter' => $this->shelter]);
    }
}
