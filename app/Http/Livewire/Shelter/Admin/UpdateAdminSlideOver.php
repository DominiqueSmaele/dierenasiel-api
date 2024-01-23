<?php

namespace App\Http\Livewire\Shelter\Admin;

use App\Http\Livewire\Shelter\Admin\Concerns\ValidatesAdmin;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\SlideOver\SlideOver;

class UpdateAdminSlideOver extends SlideOver
{
    use AuthorizesRequests,
        ValidatesAdmin;

    public User $user;

    public Shelter $shelter;

    public function mount(int $userId) : void
    {
        $this->user = User::find($userId);
    }

    public function booted() : void
    {
        $this->authorize('updateAdmin', $this->user);
    }

    public function update() : void
    {
        $this->validate();

        DB::transaction(function () {
            $this->user->save();
        });

        $this->close(andDispatch: [
            'adminUpdated',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.admin.update-admin-slide-over');
    }
}
