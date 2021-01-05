<?php

namespace Statamic\Eloquent\Listeners;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Statamic\Events\CollectionSaved;

class CreateCollectionFiles
{
    /**
     * Handle the given event.
     */
    public function handle(CollectionSaved $event)
    {
        // create model file from stub
        // create migration from stub
        // generate:model city --migration --force
        $modelName = (string) Str::of($event->collection->handle())->singular();
        Log::error('About to run: '."generate:model {$modelName} --migration --force");
        Artisan::call('generate:model {} --migration --force');
        Log::error('model and migration created');

        // Artisan::call('migrate');
    }
}
