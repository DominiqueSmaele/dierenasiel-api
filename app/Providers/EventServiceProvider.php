<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAdded;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot() : void
    {
        $this->registerObservers();
        $this->registerAnonymousListeners();
    }

    protected function registerObservers() : void
    {
        User::observe(UserObserver::class);
    }

    protected function registerAnonymousListeners() : void
    {
        Event::listen(function (MediaHasBeenAdded $event) {
            if (! Str::startsWith($event->media->mime_type, 'image/')) {
                return;
            }

            stream_copy_to_stream($event->media->stream(), $tmpFile = tmpfile());

            $dimensions = @getimagesize(stream_get_meta_data($tmpFile)['uri']);

            if (! $dimensions) {
                return;
            }

            $event->media->setCustomProperty('dimensions', [
                'width' => $dimensions[0],
                'height' => $dimensions[1],
            ])->save();
        });
    }
}
