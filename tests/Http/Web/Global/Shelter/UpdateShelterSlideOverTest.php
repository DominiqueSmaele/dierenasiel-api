<?php

namespace Tests\Http\Web\Global\Shelter;

use App\Http\Livewire\Global\Shelter\UpdateShelterSlideOver;
use App\Models\Country;
use App\Models\Shelter;
use App\Policies\AdminDashboard\ShelterPolicy;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Spatie\Geocoder\Exceptions\CouldNotGeocode;
use Spatie\Geocoder\Facades\Geocoder;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class UpdateShelterSlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public UploadedFile $image;

    public string $name;

    public string $email;

    public string $phone;

    public string $street;

    public string $streetNumber;

    public string $boxNumber;

    public string $zipcode;

    public string $city;

    public Country $country;

    public float $longitude;

    public float $latitude;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();

        $this->image = UploadedFile::fake()->image('image.png');
        $this->name = $this->faker->name();
        $this->email = $this->faker->email();
        $this->phone = $this->faker->e164PhoneNumber();
        $this->street = $this->faker->streetName();
        $this->streetNumber = $this->faker->randomNumber();
        $this->boxNumber = $this->faker->randomLetter();
        $this->zipcode = $this->faker->postcode();
        $this->city = $this->faker->city();
        $this->country = Country::factory()->create();
        $this->longitude = $this->faker->longitude();
        $this->latitude = $this->faker->latitude();
    }

    /** @test */
    public function it_updates_shelter()
    {
        Geocoder::shouldReceive('setCountry')
            ->with($this->country->code)
            ->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('getCoordinatesForAddress')
            ->with("{$this->street} {$this->streetNumber} {$this->boxNumber}, {$this->zipcode} {$this->city}, {$this->country->code}")
            ->once()
            ->andReturn(['lng' => $this->longitude, 'lat' => $this->latitude]);

        Livewire::test(UpdateShelterSlideOver::class, [$this->shelter->id])
            ->set('image', $this->image)
            ->set('shelter.name', $this->name)
            ->set('shelter.email', $this->email)
            ->set('phone', $this->phone)
            ->set('address.street', $this->street)
            ->set('address.number', $this->streetNumber)
            ->set('address.box_number', $this->boxNumber)
            ->set('address.zipcode', $this->zipcode)
            ->set('address.city', $this->city)
            ->set('address.country_id', $this->country->id)
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('shelterUpdated')
            ->assertDispatched('slide-over.close');

        $dbShelter = Shelter::first();

        $this->assertNotNull($dbShelter);
        $this->assertCount(1, $dbShelter->getMedia('image'));
        $this->assertSame($this->name, $dbShelter->name);
        $this->assertSame($this->email, $dbShelter->email);
        $this->assertSame($this->phone, $dbShelter->phone->formatE164());

        $this->assertSame($this->street, $dbShelter->address->street);
        $this->assertSame($this->streetNumber, $dbShelter->address->number);
        $this->assertSame($this->boxNumber, $dbShelter->address->box_number);
        $this->assertSame($this->zipcode, $dbShelter->address->zipcode);
        $this->assertSame($this->country->id, $dbShelter->address->country->id);

        $this->assertSame($this->latitude, $dbShelter->address->coordinates->latitude);
        $this->assertSame($this->longitude, $dbShelter->address->coordinates->longitude);
    }

    /** @test */
    public function it_updates_shelter_without_updating_email()
    {
        Geocoder::shouldReceive('setCountry->getCoordinatesForAddress')->andReturn(['lat' => $this->latitude, 'lng' => $this->longitude]);

        Livewire::test(UpdateShelterSlideOver::class, [$this->shelter->id])
            ->set('image', $this->image)
            ->set('shelter.name', $this->name)
            ->set('shelter.email', $this->shelter->email)
            ->set('phone', $this->phone)
            ->set('address.street', $this->street)
            ->set('address.number', $this->streetNumber)
            ->set('address.box_number', $this->boxNumber)
            ->set('address.zipcode', $this->zipcode)
            ->set('address.city', $this->city)
            ->set('address.country_id', $this->country->id)
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('shelterUpdated')
            ->assertDispatched('slide-over.close');
    }

    /** @test */
    public function it_updates_shelter_without_calling_google_api_if_address_has_not_been_updated()
    {
        $geocoderSpy = Geocoder::spy();

        Livewire::test(UpdateShelterSlideOver::class, [$this->shelter->id])
            ->set('image', $this->image)
            ->set('shelter.name', $this->name)
            ->set('shelter.email', $this->email)
            ->set('phone', $this->phone)
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('shelterUpdated')
            ->assertDispatched('slide-over.close');

        $geocoderSpy->shouldNotHaveBeenCalled();
    }

    /** @test */
    public function it_throws_validation_error_if_address_could_not_be_geocoded()
    {
        Geocoder::shouldReceive('setCountry->getCoordinatesForAddress')->once()->andThrow(new CouldNotGeocode());

        Livewire::test(UpdateShelterSlideOver::class, [$this->shelter->id])
            ->set('image', $this->image)
            ->set('shelter.name', $this->name)
            ->set('shelter.email', $this->email)
            ->set('phone', $this->phone)
            ->set('address.street', $this->street)
            ->set('address.number', $this->streetNumber)
            ->set('address.box_number', $this->boxNumber)
            ->set('address.zipcode', $this->zipcode)
            ->set('address.city', $this->city)
            ->set('address.country_id', $this->country->id)
            ->call('update')
            ->assertHasErrors(['address'])
            ->assertSee(__('validation.custom.address.geocode'));
    }

    /** @test */
    public function it_throws_validation_error_if_email_is_not_unique()
    {
        Shelter::factory()->create(['email' => $this->email]);

        Livewire::test(UpdateShelterSlideOver::class, [$this->shelter->id])
            ->set('image', $this->image)
            ->set('shelter.name', $this->name)
            ->set('shelter.email', $this->email)
            ->set('phone', $this->phone)
            ->set('address.street', $this->street)
            ->set('address.number', $this->streetNumber)
            ->set('address.box_number', $this->boxNumber)
            ->set('address.zipcode', $this->zipcode)
            ->set('address.city', $this->city)
            ->set('address.country_id', $this->country->id)
            ->call('update')
            ->assertHasErrors(['shelter.email']);
    }

    /** @test */
    public function it_throws_validation_error_if_required_data_is_missing()
    {
        Livewire::test(UpdateShelterSlideOver::class, [$this->shelter->id])
            ->set('image', null)
            ->set('shelter.name', null)
            ->set('shelter.email', null)
            ->set('phone', null)
            ->set('address.street', null)
            ->set('address.number', null)
            ->set('address.box_number', null)
            ->set('address.zipcode', null)
            ->set('address.city', null)
            ->set('address.country_id', null)
            ->call('update')
            ->assertHasErrors([
                'shelter.name',
                'shelter.email',
                'phone',
                'address.street',
                'address.number',
                'address.zipcode',
                'address.city',
                'address.country_id',
            ])
            ->assertHasNoErrors([
                'image',
                'address.box_number',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_update_shelter_allowed_by_policy()
    {
        $this->partialMockPolicy(ShelterPolicy::class)->forUser($this->user)->shouldAllow('update', $this->shelter);

        Livewire::test(UpdateShelterSlideOver::class, [$this->shelter->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_update_shelter_denied_by_policy()
    {
        $this->partialMockPolicy(ShelterPolicy::class)->forUser($this->user)->shouldDeny('update', $this->shelter);

        Livewire::test(UpdateShelterSlideOver::class, [$this->shelter->id])->assertForbidden();
    }
}
