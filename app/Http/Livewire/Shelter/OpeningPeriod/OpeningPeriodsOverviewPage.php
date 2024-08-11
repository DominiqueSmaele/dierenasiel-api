<?php

namespace App\Http\Livewire\Shelter\OpeningPeriod;

use App\Models\OpeningPeriod;
use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class OpeningPeriodsOverviewPage extends Component
{
    use AuthorizesRequests;

    public Shelter $shelter;

    protected $listeners = [
        'openingPeriodsCreated' => '$refresh',
        'openingPeriodsUpdated' => '$refresh',
    ];

    public function booted() : void
    {
        $this->authorize('view', [OpeningPeriod::class, $this->shelter]);
    }

    public function render() : View
    {
        return view('livewire.shelter.opening-period.opening-periods-overview-page', [
            'openingPeriods' => OpeningPeriod::query()
                ->where('shelter_id', $this->shelter->id)
                ->orderBy('day')
                ->get(),
        ])->layout('layouts.shelter', ['shelter' => $this->shelter]);
    }
}
