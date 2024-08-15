<?php

namespace Tests\Http\Web\User;

use App\Http\Livewire\User\DeleteUserPage;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteUserPageTest extends TestCase
{
    public User $user;

    public string $email;

    public string $password;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => $password = str()->password()]);

        $this->email = $this->user->email;
        $this->password = $password;
    }

    /** @test */
    public function it_deletes_user()
    {
        Livewire::test(DeleteUserPage::class)
            ->set('email', $this->email)
            ->set('password', $this->password)
            ->call('delete')
            ->assertHasNoErrors();

        $dbUser = User::withTrashed()->find($this->user->id);

        $this->assertNotNull($dbUser);
        $this->assertTrue($dbUser->trashed());

        $this->assertSame('anonymized', $dbUser->firstname);
        $this->assertSame('anonymized', $dbUser->lastname);
        $this->assertSame("anonymized{$this->user->id}@example.com", $dbUser->email);
    }

    /** @test */
    public function it_throws_validation_error_if_email_is_not_linked_to_a_user()
    {
        Livewire::test(DeleteUserPage::class)
            ->set('email', $this->faker->unique()->safeEmail())
            ->set('password', $this->password)
            ->call('delete')
            ->assertHasErrors('email');
    }

    /** @test */
    public function it_throws_validation_error_if_password_does_not_match_user_password()
    {
        Livewire::test(DeleteUserPage::class)
            ->set('email', $this->email)
            ->set('password', str()->password())
            ->call('delete')
            ->assertHasErrors('email');
    }

    /** @test */
    public function it_throws_validation_errors_if_required_data_is_missing()
    {
        Livewire::test(DeleteUserPage::class)
            ->set('email', null)
            ->set('password', null)
            ->call('delete')
            ->assertHasErrors([
                'email',
                'password',
            ]);
    }
}
