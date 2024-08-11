<?php

namespace App\Http\Livewire\Shelter\OpeningPeriod;

use App\Http\Livewire\Shelter\OpeningPeriod\Concerns\ValidatesOpeningPeriod;
use App\Models\OpeningPeriod;
use App\Models\Shelter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\SlideOver\SlideOver;

class UpdateOpeningPeriodSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesOpeningPeriod;

    public Shelter $shelter;

    public function mount(int $shelterId) : void
    {
        $this->shelter = Shelter::find($shelterId);
    }

    public function booted() : void
    {
        $this->authorize('update', [OpeningPeriod::class, $this->shelter]);
    }

    public function update() : void
    {
        $this->validate();

        DB::transaction(function () {
            foreach ($this->openingPeriods as $openingPeriod) {
                $openingPeriod->save();
            }
        });

        $this->close(andDispatch: [
            'openingPeriodsUpdated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.opening-period.update-opening-periods-slide-over');
    }
}
