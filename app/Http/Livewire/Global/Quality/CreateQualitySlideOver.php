<?php

namespace App\Http\Livewire\Global\Quality;

use App\Http\Livewire\Global\Quality\Concerns\ValidatesQuality;
use App\Models\Quality;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateQualitySlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesQuality;

    public Quality $quality;

    public function booted() : void
    {
        $this->authorize('create', Quality::class);
    }

    public function create() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->quality->save();
        });

        $this->close(
            andDispatch: [
                'qualityCreated',
            ]
        );
    }

    public function render() : View
    {
        return view('livewire.global.quality.create-quality-slide-over');
    }
}
