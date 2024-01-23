<?php

namespace App\Http\Livewire\Global\Quality;

use App\Models\Quality;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class QualitiesOverviewPage extends Component
{
    use AuthorizesRequests,
        withPagination;

    #[Url(as: 'q')]
    public ?int $filterValue = null;

    protected $listeners = [
        'qualityCreated' => '$refresh',
        'qualityUpdated' => '$refresh',
    ];

    public function booted() : void
    {
        $this->authorize('viewAny', Quality::class);
    }

    public function render() : View
    {
        return view('livewire.global.quality.qualities-overview-page', [
            'qualities' => Quality::query()
                ->when($this->filterValue)->where('type_id', $this->filterValue)
                ->with('type')
                ->orderBy('type_id')
                ->orderBy('name')
                ->orderBy('id')
                ->paginate(30),
        ])->layout('layouts.global');
    }
}
