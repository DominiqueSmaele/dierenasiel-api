<?php

namespace App\Http\Livewire\Global\Shelter\Concerns;

use App\Models\Address;
use App\Models\Country;
use App\Models\Shelter;
use App\Models\Values\Point;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\Geocoder\Exceptions\CouldNotGeocode;
use Spatie\Geocoder\Facades\Geocoder;

trait ValidatesShelter
{
    public Shelter $shelter;

    public Address $address;

    public ?string $phone;

    public TemporaryUploadedFile | string | null $image = null;

    public bool $withoutImage = false;

    public function mountValidatesShelter() : void
    {
        $this->shelter ??= Shelter::make();
        $this->address = $this->shelter->address ?? Address::make();
        $this->phone = $this->shelter->phone;
        $this->withoutImage = $this->shelter->image === null;
    }

    public function bootedValidatesShelter() : void
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $this->shelter->phone = $this->phone;

                if ($this->withoutImage) {
                    $this->image = null;
                }

                if ($this->address->isClean(['street', 'number', 'box_number', 'zipcode', 'city', 'country_id'])) {
                    return;
                }

                $country = Country::find($this->address->country_id)->code;

                try {
                    $coordinates = Geocoder::setCountry($country)->getCoordinatesForAddress(
                        "{$this->address->street} {$this->address->number} {$this->address->box_number}, " .
                        "{$this->address->zipcode} {$this->address->city}, {$country}"
                    );

                    if (in_array('result_not_found', $coordinates, true)) {
                        throw new CouldNotGeocode();
                    }

                    if (isset($coordinates['partial_match']) && $coordinates['partial_match'] === true) {
                        throw new CouldNotGeocode();
                    }

                    $this->address->coordinates = new Point($coordinates['lat'], $coordinates['lng'], 4326);
                } catch (CouldNotGeocode) {
                    return $validator->errors()->add('address', __('validation.custom.address.geocode'));
                }
            });
        });
    }

    public function updatedImage() : void
    {
        $this->withoutImage = $this->image === null;
    }

    protected function rules() : array
    {
        return [
            'shelter.name' => [
                'required',
                'string',
                'max:255',
            ],
            'shelter.email' => [
                'required',
                'email',
                Rule::unique(Shelter::class, 'email')->ignore($this->shelter),
                'max:255',
            ],
            'shelter.facebook' => [
                'nullable',
                'string',
                'max:255',
            ],
            'shelter.instagram' => [
                'nullable',
                'string',
                'max:255',
            ],
            'shelter.tiktok' => [
                'nullable',
                'string',
                'max:255',
            ],
            'address.street' => [
                'required',
                'string',
                'max:255',
            ],
            'address.number' => [
                'required',
                'string',
                'max:255',
            ],
            'address.box_number' => [
                'nullable',
                'string',
                'max:255',
            ],
            'address.zipcode' => [
                'required',
                'string',
                'max:255',
            ],
            'address.city' => [
                'required',
                'string',
                'max:255',
            ],
            'address.country_id' => [
                'required',
                'integer',
                Rule::exists(Country::class, 'id'),
            ],
            'image' => [
                'nullable',
                'image',
                'max:10000',
            ],
            'phone' => [
                'required',
                'phone:BE',
                'max:255',
            ],
        ];
    }
}
