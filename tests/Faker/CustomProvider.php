<?php

namespace Tests\Faker;

use Faker\Provider\Base;
use libphonenumber\PhoneNumberUtil;

class CustomProvider extends Base
{
    public function e164PhoneNumber() : string
    {
        return self::randomElement([
            '+32494123456',
            '+12025550185',
            '+16135550128',
            '+611900654321',
            '+3655917370',
            '+33932415996',
            '+31716561151',
            '+4930758961503',
            '+39062110371',
            '+34637701178',
        ]);
    }

    public function countryCode() : string
    {
        return self::randomElement(PhoneNumberUtil::getInstance()->getSupportedRegions());
    }

    public function null()
    {
        return null;
    }

    public function translatable() : TranslatableGenerator
    {
        return new TranslatableGenerator($this->generator);
    }
}
