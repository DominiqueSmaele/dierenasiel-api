<?php

namespace Tests\Faker;

use Faker\Generator;

class TranslatableGenerator
{
    protected ?array $locales = null;

    public function __construct(
        protected Generator $generator
    ) {
    }

    public function locales($locales) : self
    {
        $this->locales = $locales;

        return $this;
    }

    public function __call($name, $arguments) : mixed
    {
        return collect($this->locales ?? config('app.supported_locales'))->mapWithKeys(function (string $locale) use ($name, $arguments) {
            return [$locale => call_user_func_array([$this->generator, $name], $arguments)];
        })->all();
    }
}
