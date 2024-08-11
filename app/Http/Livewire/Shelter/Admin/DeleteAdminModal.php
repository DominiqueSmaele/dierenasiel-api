<?php

namespace App\Http\Livewire\Shelter\Admin;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use WireElements\Pro\Components\Modal\Modal;

class DeleteAdminModal extends Modal
{
    use AuthorizesRequests;

    public User $user;

    public function mount(int $userId) : void
    {
        $this->user = User::find($userId);
    }

    public function booted() : void
    {
        $this->authorize('deleteAdmin', $this->user);
    }

    public function delete() : void
    {
        DB::transaction(fn () => $this->user->delete());

        $this->close(withForce: true, andDispatch: [
            'adminDeleted',
        ]);
    }

    public function render() : View
    {
        return view('livewire.shelter.admin.delete-admin-modal');
    }
}
