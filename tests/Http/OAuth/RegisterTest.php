<?php

namespace Tests\Http\Oauth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    protected string $firstname;

    protected string $lastname;

    protected string $email;

    protected string $password;

    public function setUp() : void
    {
        parent::setUp();

        $this->artisan('passport:install');

        $this->firstname = $this->faker->firstName();
        $this->lastname = $this->faker->lastName();
        $this->email = $this->faker->unique()->safeEmail();
        $this->password = str()->password();
    }

    /** @test */
    public function it_registers_user_with_provided_data()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => $this->password,
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'token',
            ]);

        $dbUser = User::first();

        $this->assertNotNull($dbUser);

        $this->assertSame($this->firstname, $dbUser->firstname);
        $this->assertSame($this->lastname, $dbUser->lastname);
        $this->assertSame($this->email, $dbUser->email);
        $this->assertTrue(Hash::check($this->password, $dbUser->password));
    }

    /** @test */
    public function it_does_not_register_when_email_is_not_unique()
    {
        $user = User::factory()->create();

        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $user->email,
            'password' => $this->password,
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_email_is_invalid()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => Str::random(),
            'password' => $this->password,
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_password_is_less_than_8_characters()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => str()->password(6),
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_password_is_missing_a_lowercase_letter()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => strtolower(str()->password()),
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_password_is_missing_an_uppercase_letter()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => strtoupper(str()->password()),
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_password_is_missing_a_number()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => str()->password($numbers = false),
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_password_is_compromised()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => 'Testing123',
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_firstname_is_missing()
    {
        $this->postJson('/api/register', [
            'firstname' => null,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => $this->password,
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_lastname_is_missing()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => null,
            'email' => $this->email,
            'password' => $this->password,
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_email_is_missing()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => null,
            'password' => $this->password,
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_does_not_register_when_password_is_missing()
    {
        $this->postJson('/api/register', [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => null,
        ])
            ->assertStatus(422);
    }
}
