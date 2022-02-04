<?php namespace App\Models\Slideshows;

class Slideshow extends \TakeoffDesignGroup\CMS\Models\Slideshows\Slideshow
{
    public function slides()
    {
        return $this->hasMany(\App\Models\Slideshows\Slide::class, 'slideshow_id');
    }

	public function scopePublished($query, $slideshow_id, $site_id = 1)
	{
        return $query->with(['slides' => function($query)
			{
                $query->public()
                    ->orderBy('rank');
			}])
			->where('id', '=', $slideshow_id)
			->where('site_id', '=', $site_id)
			->where('published', '=', 1);
	}
}