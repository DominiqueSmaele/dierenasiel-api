<?php

namespace Tests\Http\Web\Shelter\Admin;

use App\Enums\ShelterRole;
use App\Http\Livewire\Shelter\Admin\UpdateAdminSlideOver;
use App\Models\User;
use App\Policies\AdminDashboard\UserPolicy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class UpdateAdminSlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public User $selectedUser;

    public string $firstname;

    public string $lastname;

    public string $email;

    public string $password;

    public string $passwordRepeat;

    public function setUp() : void
    {
        parent::setUp();

        $this->selectedUser = User::factory()->assignShelterRole(ShelterRole::admin)->create();

        $this->firstname = $this->faker->firstName();
        $this->lastname = $this->faker->lastName();
        $this->email = $this->faker->unique()->safeEmail();
        $this->password = Str::random(7) . rand(0, 9);
        $this->passwordRepeat = $this->password;
    }

    /** @test */
    public function it_updates_admin()
    {
        Livewire::test(UpdateAdminSlideOver::class, [$this->selectedUser->id])
            ->set('user.firstname', $this->firstname)
            ->set('user.lastname', $this->lastname)
            ->set('user.email', $this->email)
            ->set('password', $this->password)
            ->set('passwordRepeat', $this->passwordRepeat)
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('adminUpdated')
            ->assertDispatched('slide-over.close');

        $dbAdmin = User::firstWhere('email', $this->email);

        $this->assertSame($this->firstname, $dbAdmin->firstname);
        $this->assertSame($this->lastname, $dbAdmin->lastname);
        $this->assertSame($this->email, $dbAdmin->email);
        $this->assertTrue(Hash::check($this->password, $dbAdmin->password));
    }

    /** @test */
    public function it_updates_admin_without_updating_email()
    {
        Livewire::test(UpdateAdminSlideOver::class, [$this->selectedUser->id])
            ->set('user.firstname', $this->firstname)
            ->set('user.lastname', $this->lastname)
            ->set('user.email', $this->selectedUser->email)
            ->set('password', $this->password)
            ->set('passwordRepeat', $this->passwordRepeat)
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('adminUpdated')
            ->assertDispatched('slide-over.close');
    }

    /** @test */
    public function it_throws_validation_error_if_email_is_not_unique()
    {
        $user = User::factory()->create(['email' => $this->email]);

        Livewire::test(UpdateAdminSlideOver::class, [$this->selectedUser->id])
            ->set('user.firstname', $this->firstname)
            ->set('user.lastname', $this->lastname)
            ->set('user.email', $this->email)
            ->set('password', $this->password)
            ->set('passwordRepeat', $this->passwordRepeat)
            ->call('update')
            ->assertHasErrors('user.email');
    }

    /** @test */
    public function it_throws_validation_error_if_repeat_password_does_not_match_password()
    {
        Livewire::test(UpdateAdminSlideOver::class, [$this->selectedUser->id])
            ->set('user.firstname', $this->firstname)
            ->set('user.lastname', $this->lastname)
            ->set('user.email', $this->email)
            ->set('password', $this->password)
            ->set('passwordRepeat', Str::random(7) . rand(0, 9))
            ->call('update')
            ->assertHasErrors('passwordRepeat');
    }

    /** @test */
    public function it_throws_validation_errors_if_required_data_is_missing()
    {
        Livewire::test(UpdateAdminSlideOver::class, [$this->selectedUser->id])
            ->set('user.firstname', null)
            ->set('user.lastname', null)
            ->set('user.email', null)
            ->set('password', null)
            ->set('passwordRepeat', null)
            ->call('update')
            ->assertHasErrors([
                'user.firstname',
                'user.lastname',
                'user.email',
            ])
            ->assertHasNoErrors([
                'password',
                'passwordRepeat',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_update_admin_allowed_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldAllow('updateAdmin', $this->selectedUser);

        Livewire::test(UpdateAdminSlideOver::class, ['userId' => $this->selectedUser->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_update_admin_denied_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldDeny('updateAdmin', $this->selectedUser);

        Livewire::test(UpdateAdminSlideOver::class, ['userId' => $this->selectedUser->id])->assertForbidden();
    }
}
