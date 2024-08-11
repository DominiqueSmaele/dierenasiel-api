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
