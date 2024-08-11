<?php

namespace Tests\Http\Web\Shelter\Admin;

use App\Enums\ShelterRole;
use App\Http\Livewire\Shelter\Admin\CreateAdminSlideOver;
use App\Models\Shelter;
use App\Models\User;
use App\Policies\AdminDashboard\UserPolicy;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class CreateAdminSlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public string $firstname;

    public string $lastname;

    public string $email;

    public string $password;

    public string $passwordRepeat;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();

        $this->firstname = $this->faker->firstName();
        $this->lastname = $this->faker->lastName();
        $this->email = $this->faker->unique()->safeEmail();
        $this->password = str()->password();
        $this->passwordRepeat = $this->password;
    }

    /** @test */
    public function it_creates_admin()
    {
        Livewire::test(CreateAdminSlideOver::class, [$this->shelter->id])
            ->set('user.firstname', $this->firstname)
            ->set('user.lastname', $this->lastname)
            ->set('user.email', $this->email)
            ->set('password', $this->password)
            ->set('passwordRepeat', $this->passwordRepeat)
            ->call('create')
            ->assertHasNoErrors()
            ->assertDispatched('adminCreated')
            ->assertDispatched('slide-over.close');

        $dbAdmin = User::firstWhere('email', $this->email);

        $this->assertNotNull($dbAdmin);

        $this->assertSame($this->firstname, $dbAdmin->firstname);
        $this->assertSame($this->lastname, $dbAdmin->lastname);
        $this->assertSame($this->email, $dbAdmin->email);
        $this->assertTrue(Hash::check($this->password, $dbAdmin->password));
    }

    /** @test */
    public function it_creates_admin_with_role()
    {
        Livewire::test(CreateAdminSlideOver::class, [$this->shelter->id])
            ->set('user.firstname', $this->firstname)
            ->set('user.lastname', $this->lastname)
            ->set('user.email', $this->email)
            ->set('password', $this->password)
            ->set('passwordRepeat', $this->passwordRepeat)
            ->call('create')
            ->assertHasNoErrors()
            ->assertDispatched('adminCreated')
            ->assertDispatched('slide-over.close');

        $dbAdmin = User::firstWhere('email', $this->email);

        $this->assertNotNull($dbAdmin);
        $this->assertSame(ShelterRole::admin->value, $dbAdmin->roles()->get()->last()->name);
    }

    /** @test */
    public function it_throws_validation_error_if_email_is_not_unique()
    {
        $user = User::factory()->create(['email' => $this->email]);

        Livewire::test(CreateAdminSlideOver::class, [$this->shelter->id])
            ->set('user.firstname', $this->firstname)
            ->set('user.lastname', $this->lastname)
            ->set('user.email', $this->email)
            ->set('password', $this->password)
            ->set('passwordRepeat', $this->passwordRepeat)
            ->call('create')
            ->assertHasErrors('user.email');
    }

    /** @test */
    public function it_throws_validation_error_if_repeat_password_does_not_match_password()
    {
        Livewire::test(CreateAdminSlideOver::class, [$this->shelter->id])
            ->set('user.firstname', $this->firstname)
            ->set('user.lastname', $this->lastname)
            ->set('user.email', $this->email)
            ->set('password', $this->password)
            ->set('passwordRepeat', str()->password())
            ->call('create')
            ->assertHasErrors('passwordRepeat');
    }

    /** @test */
    public function it_throws_validation_errors_if_required_data_is_missing()
    {
        Livewire::test(CreateAdminSlideOver::class, [$this->shelter->id])
            ->set('user.firstname', null)
            ->set('user.lastname', null)
            ->set('user.email', null)
            ->set('password', null)
            ->set('passwordRepeat', null)
            ->call('create')
            ->assertHasErrors([
                'user.firstname',
                'user.lastname',
                'user.email',
                'password',
                'passwordRepeat',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_create_admin_allowed_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldAllow('createAdmin', $this->shelter);

        Livewire::test(CreateAdminSlideOver::class, ['shelterId' => $this->shelter->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_create_admin_denied_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldDeny('createAdmin', $this->shelter);

        Livewire::test(CreateAdminSlideOver::class, ['shelterId' => $this->shelter->id])->assertForbidden();
    }
}
