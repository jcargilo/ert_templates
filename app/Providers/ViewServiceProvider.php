<?php
 
namespace App\Providers;

use App\Models\EventsAPI;
use App\View\Composers\ProfileComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
 
class ViewServiceProvider extends ServiceProvider
{ 
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('site._templates.events', function ($view) {
            $view->with('upcomingEvents', 
                request()->path() === 'events'    
                    ? (new EventsAPI)->getUpcomingEvents()
                    : (new EventsAPI)->getUpcomingEvents(3)
            );
        });

        View::composer('site._templates.past_events', function ($view) {
            $view->with('pastEvents', (new EventsAPI)->getPastEvents());
        });
    }
}