<?php

namespace App\Providers;

use App\Events\MessageSent;
use App\Listeners\StoreMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    { 
        Event::listen(function (MessageSent $event) {
            dispatch(new StoreMessage($event));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
    }
}
