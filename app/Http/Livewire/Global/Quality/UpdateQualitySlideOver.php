<?php

namespace App\Http\Livewire\Global\Quality;

use App\Http\Livewire\Global\Quality\Concerns\ValidatesQuality;
use App\Models\Quality;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\SlideOver\SlideOver;

class UpdateQualitySlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesQuality;

    public Quality $quality;

    public function mount(int $qualityId) : void
    {
        $this->quality = Quality::find($qualityId);
    }

    public function booted() : void
    {
        $this->authorize('update', $this->quality);
    }

    public function update() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->quality->save();
        });

        $this->close(andDispatch: [
            'qualityUpdated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.global.quality.update-quality-slide-over');
    }
}
