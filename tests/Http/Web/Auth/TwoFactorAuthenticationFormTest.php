<?php

namespace Tests\Http\Web\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Http\Livewire\TwoFactorAuthenticationForm;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class TwoFactorAuthenticationFormTest extends TestCase
{
    use AuthenticateAsWebUser;

    /** @test */
    public function it_is_shown_on_user_profile_route()
    {
        $this->get('/user/profile')
            ->assertStatus(200)
            ->assertSeeLivewire(TwoFactorAuthenticationForm::class);
    }

    /** @test */
    public function it_can_enable_two_factor_authentication()
    {
        Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('enableTwoFactorAuthentication')
            ->assertSuccessful();

        $dbUser = User::find($this->user->id);

        $this->assertNotNull($dbUser->two_factor_secret);
        $this->assertCount(8, $dbUser->recoveryCodes());
        $this->assertNull($dbUser->two_factor_confirmed_at);
    }

    /** @test */
    public function it_can_regenerate_recovery_codes()
    {
        $this->user->update(['two_factor_recovery_codes' => Str::random()]);

        Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('regenerateRecoveryCodes')
            ->assertSuccessful();

        $dbUser = User::find($this->user->id);

        $this->assertNotEquals($this->user->two_factor_recovery_codes, $dbUser->two_factor_recovery_codes);
        $this->assertCount(8, $dbUser->recoveryCodes());
    }

    /** @test */
    public function it_can_disable_two_factor_authentication()
    {
        Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('disableTwoFactorAuthentication')
            ->assertSuccessful();

        $dbUser = User::find($this->user->id);

        $this->assertNull($dbUser->two_factor_secret);
        $this->assertNull($dbUser->two_factor_recovery_codes);
        $this->assertNull($dbUser->two_factor_confirmed_at);
    }
}
