<?php

namespace Tests\Http\Web\Shelter\Animal;

use App\Http\Livewire\Shelter\Animal\CreateAnimalSlideOver;
use App\Models\Animal;
use App\Models\Shelter;
use App\Models\Type;
use App\Policies\AdminDashboard\AnimalPolicy;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class CreateAnimalSlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public UploadedFile $image;

    public string $name;

    public Type $type;

    public string $sex;

    public int $years;

    public int $months;

    public string $race;

    public string $description;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();

        $this->image = UploadedFile::fake()->image('image.png');
        $this->name = $this->faker->name();
        $this->type = Type::factory()->create();
        $this->sex = $this->faker->randomElement(['mannelijk', 'vrouwelijk']);
        $this->years = $this->faker->numberBetween(0, 25);
        $this->months = $this->faker->numberBetween(0, 11);
        $this->race = $this->faker->word();
        $this->description = $this->faker->text();
    }

    /** @test */
    public function it_creates_animal()
    {
        Livewire::test(CreateAnimalSlideOver::class, [$this->shelter->id])
            ->set('image', $this->image)
            ->set('animal.name', $this->name)
            ->set('animal.type_id', $this->type->id)
            ->set('animal.sex', $this->sex)
            ->set('animal.years', $this->years)
            ->set('animal.months', $this->months)
            ->set('animal.race', $this->race)
            ->set('animal.description', $this->description)
            ->call('create')
            ->assertHasNoErrors()
            ->assertDispatched('animalCreated')
            ->assertDispatched('slide-over.close');

        $dbAnimal = Animal::first();

        $this->assertNotNull($dbAnimal);
        $this->assertCount(1, $dbAnimal->getMedia('image'));
        $this->assertSame($this->name, $dbAnimal->name);
        $this->assertSame($this->type->id, $dbAnimal->type->id);
        $this->assertSame($this->sex, $dbAnimal->sex);
        $this->assertSame($this->years, $dbAnimal->years);
        $this->assertSame($this->months, $dbAnimal->months);
        $this->assertSame($this->race, $dbAnimal->race);
        $this->assertSame($this->description, $dbAnimal->description);
    }

    /** @test */
    public function it_throws_validation_error_if_required_data_is_missing()
    {
        Livewire::test(CreateAnimalSlideOver::class, [$this->shelter->id])
            ->set('image', null)
            ->set('animal.name', null)
            ->set('animal.type_id', null)
            ->set('animal.sex', null)
            ->set('animal.years', null)
            ->set('animal.months', null)
            ->set('animal.race', null)
            ->set('animal.description', null)
            ->call('create')
            ->assertHasErrors([
                'image',
                'animal.name',
                'animal.type_id',
                'animal.sex',
                'animal.description',
            ])
            ->assertHasNoErrors([
                'animal.years',
                'animal.months',
                'animal.race',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_create_animal_allowed_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldAllow('create', $this->shelter);

        Livewire::test(CreateAnimalSlideOver::class, [$this->shelter->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_create_animal_denied_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldDeny('create', $this->shelter);

        Livewire::test(CreateAnimalSlideOver::class, [$this->shelter->id])->assertForbidden();
    }
}
