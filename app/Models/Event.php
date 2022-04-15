<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class Event
{
    public $title;
    public $image;
    public $startDate;
    public $endDate;
    public $type;
    public $url;

    public function __construct($data)
    {
        $this->title = Arr::get($data, 'title', 'Event Title');
        $this->image = Arr::get($data, 'images');
        $this->startDate = !empty($data['startDate']) 
            ? Carbon::parse($data['startDate'])->setTimezone($data['timezone'] ?? config('app.timezone')) 
            : NULL;
        $this->endDate = !empty($data['endDate']) 
            ? Carbon::parse($data['endDate'])->setTimezone($data['timezone'] ?? config('app.timezone')) 
            : NULL;
        $this->type = Arr::get($data, 'campaignType');
        $this->url = Arr::get($data, 'landingPageUrl');
    }
}