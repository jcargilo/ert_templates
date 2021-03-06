<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use TakeoffDesignGroup\CMS\Models\Sites\Site;
use Cache;

class EventsAPI
{
    public $events;

    public function __construct()
    {
        $this->fetchFromAPI();
    }

    public function getPastEvents(int $limit = NULL) : Collection
    {
        $events = $this->events->filter(function ($event) {
            return $event->startDate->lte(\Carbon\Carbon::now());
        });

        return ($limit ? $events->take($limit) : $events)->reverse();
    }

    public function getUpcomingEvents(int $limit = NULL) : Collection
    {
        $events = $this->events->filter(function ($event) {
            return $event->startDate->gt(\Carbon\Carbon::now());
        });

        return $limit ? $events->take($limit) : $events;
    }

    protected function fetchFromAPI() : void
    {
        $site = cache('site') ?? Site::find(1);
        
        if ((!$site->cache_data ?? false) || auth()->check()) {
            Cache::forget('events');
        }

        $this->events = Cache::remember('events', 3600, function () use ($site) {
            $events = new Collection;
            
            $url = $site->attributes['events_api_link'] ?? '';
            if ($url) {
                $response = Http::get($url);

                if ($response->successful()) {
                    $data = $response->json()['data'] ?? [];

                    foreach ($data as $item) {
                        $events->push(new Event($item));
                    }
                }
            }

            return $events;
        });
    }
}