<?php

namespace App\Http\Livewire\Global\Shelter\Concerns;

use App\Models\Address;
use App\Models\Country;
use App\Models\Shelter;
use App\Models\Values\Point;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Spatie\Geocoder\Exceptions\CouldNotGeocode;
use Spatie\Geocoder\Facades\Geocoder;

trait ValidatesShelter
{
    public Shelter $shelter;

    public Address $address;

    public ?string $phone;

    public function mountValidatesShelter() : void
    {
        $this->shelter ??= Shelter::make();
        $this->address = $this->shelter->address ?? Address::make();
        $this->phone = $this->shelter->phone;
    }

    public function bootedValidatesShelter() : void
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                if ($this->address->isClean(['street', 'number', 'box_number', 'zipcode', 'city', 'country_id'])) {
                    return;
                }

                try {
                    $coordinates = Geocoder::setCountry($this->address->country->code)->getCoordinatesForAddress(
                        "{$this->address->street} {$this->address->number} {$this->address->box_number}, " .
                        "{$this->address->zipcode} {$this->address->city}, {$this->address->country->code}"
                    );

                    if (in_array('result_not_found', $coordinates, true)) {
                        throw new CouldNotGeocode();
                    }

                    $this->address->coordinates = new Point($coordinates['lat'], $coordinates['lng'], 4326);
                } catch (CouldNotGeocode) {
                    return $validator->errors()->add('address', __('validation.custom.address.geocode'));
                }

                $this->shelter->phone = $this->phone;
            });
        });
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
            'phone' => [
                'required',
                'phone:BE',
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
        ];
    }
}
