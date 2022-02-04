<?php namespace App\Models\Slideshows;

use Carbon\Carbon;

class Slide extends \TakeoffDesignGroup\CMS\Models\Slideshows\Slide
{
    public function setStartDateAttribute($date)
    {
        $this->attributes['start_date'] = $date ? Carbon::parse($date) : NULL;
    }

    public function setEndDateAttribute($date)
    {
        $this->attributes['end_date'] = $date ? Carbon::parse($date) : NULL;
    }

	public function scopePublic($query)
    {
        return $query
            ->where('published', '=', 1)
            ->whereNull('deleted_at')
            ->where(function($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<', Carbon::now('America/New_York'));
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', Carbon::now('America/New_York'));
            });
    }
}