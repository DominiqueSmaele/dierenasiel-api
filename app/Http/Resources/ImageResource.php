<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use RuntimeException;
use Spatie\MediaLibrary\Conversions\Conversion;

class ImageResource extends JsonResource
{
    protected Model $model;

    public function ofModel(Model $model) : self
    {
        if ($this->resource === null) {
            return $this;
        }

        if ($this->model_id !== $model->id || $this->model_type !== $model->getMorphClass()) {
            throw new RuntimeException('Model does not match model of media');
        }

        $this->model = $model->withoutRelations();

        return $this;
    }

    public function toArray($request) : array
    {
        if (! isset($this->model)) {
            throw new RuntimeException('Model of media is not provided');
        }

        return [
            'original' => [
                'url' => $this->getFullUrl(),
                'width' => (int) $this->getCustomProperty('dimensions.width'),
                'height' => (int) $this->getCustomProperty('dimensions.height'),
            ],
            'conversions' => $this->getGeneratedConversions()
                ->filter()
                ->keys()
                ->filter(function (string $conversionName) {
                    if ($this->getConversion($conversionName) === null) {
                        return false;
                    }

                    $conversionWidth = $this->getConversionWidth($conversionName);
                    $conversionHeight = $this->getConversionHeight($conversionName);

                    $originalWidth = $this->getCustomProperty('dimensions.width');
                    $originalHeight = $this->getCustomProperty('dimensions.height');

                    return ($conversionWidth === null || $conversionWidth < $originalWidth)
                        && ($conversionHeight === null || $conversionHeight < $originalHeight);
                })
                ->map(function (string $conversionName) {
                    $conversionWidth = $this->getConversionWidth($conversionName);
                    $conversionHeight = $this->getConversionHeight($conversionName);

                    $originalWidth = $this->getCustomProperty('dimensions.width');
                    $originalHeight = $this->getCustomProperty('dimensions.height');

                    if ($conversionWidth === null && $conversionHeight !== null) {
                        $width = $originalWidth * ($conversionHeight / $originalHeight);
                    }

                    if ($conversionHeight === null && $conversionWidth !== null) {
                        $height = $originalHeight * ($conversionWidth / $originalWidth);
                    }

                    return [
                        'url' => $this->getFullUrl($conversionName),
                        'width' => (int) ($width ?? $conversionWidth ?? $originalWidth),
                        'height' => (int) ($height ?? $conversionHeight ?? $originalHeight),
                    ];
                })
                ->values(),
        ];
    }

    protected function getConversionWidth(string $conversionName) : ?int
    {
        return $this->getConversion($conversionName)
            ->getManipulations()
            ->getManipulationSequence()
            ->getGroups()[0]['width'] ?? null;
    }

    protected function getConversionHeight(string $conversionName) : ?int
    {
        return $this->getConversion($conversionName)
            ->getManipulations()
            ->getManipulationSequence()
            ->getGroups()[0]['height'] ?? null;
    }

    protected function getConversion(string $conversionName) : ?Conversion
    {
        if (empty($this->model->mediaConversions)) {
            $this->model->registerMediaConversions();
        }

        return collect($this->model->mediaConversions)->first(function (Conversion $conversion) use ($conversionName) {
            return $conversion->getName() === $conversionName;
        });
    }
}
