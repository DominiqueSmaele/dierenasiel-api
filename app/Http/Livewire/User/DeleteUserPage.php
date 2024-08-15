<?php

namespace App\Http\Livewire\User;

use App\Http\Livewire\User\Concerns\ValidatesUser;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class DeleteUserPage extends Component
{
    use ValidatesUser;

    public string $email;

    public string $password;

    public function delete()
    {
        $this->validate();

        DB::transaction(fn () => $this->user->delete());

        $this->reset(['email', 'password']);

        session()->flash('status', __('web.user_delete_success_message'));
    }

    public function render() : View
    {
        return view('livewire.user.delete')->layout('layouts.guest');
    }
}
