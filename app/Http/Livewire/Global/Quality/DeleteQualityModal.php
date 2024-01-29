<?php

namespace App\Http\Livewire\Global\Quality;

use App\Models\Quality;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\Modal\Modal;

class DeleteQualityModal extends Modal
{
    use AuthorizesRequests;

    public Quality $quality;

    public function mount(int $qualityId) : void
    {
        $this->quality = Quality::find($qualityId);
    }

    public function booted() : void
    {
        $this->authorize('delete', $this->quality);
    }

    public function delete() : void
    {
        DB::transaction(function () {
            $this->quality->animals()->detach();

            $this->quality->delete();
        });

        $this->close(withForce: true, andDispatch: [
            'qualityDeleted',
        ]);
    }

    public function render() : View
    {
        return view('livewire.global.quality.delete-quality-modal');
    }
}
