<?php 

namespace App\Models;

use Carbon\Carbon;
use TakeoffDesignGroup\CMS\Models\Sites\Site as CMSSite;

class Site extends CMSSite
{
    public function __construct()
    {
        $this->dates = array_merge($this->dates, [
            'choice_disabled_start_at',
            'choice_disabled_end_at',
            'tins_disabled_start_at',
            'tins_disabled_end_at',
            'ribbons_disabled_start_at',
            'ribbons_disabled_end_at',
            'gluten_free_disabled_start_at',
            'gluten_free_disabled_end_at',
            'two_dozen_disabled_start_at',
            'two_dozen_disabled_end_at',
            'three_dozen_disabled_start_at',
            'three_dozen_disabled_end_at',
        ]);

        parent::__construct();
    }

    // Attributes
    public function choiceIsDisabled()
    {
        return $this->choice_disabled && 
            ($this->choice_disabled_start_at == NULL || Carbon::now()->gte($this->choice_disabled_start_at)) &&
            ($this->choice_disabled_end_at == NULL || Carbon::now()->lt($this->choice_disabled_end_at));
    }

    public function twoDozenIsDisabled()
    {
        return $this->two_dozen_disabled && 
            ($this->two_dozen_disabled_start_at == NULL || Carbon::now()->gte($this->two_dozen_disabled_start_at)) &&
            ($this->two_dozen_disabled_end_at == NULL || Carbon::now()->lt($this->two_dozen_disabled_end_at));
    }
    
    public function threeDozenIsDisabled()
    {
        return $this->three_dozen_disabled && 
            ($this->three_dozen_disabled_start_at == NULL || Carbon::now()->gte($this->three_dozen_disabled_start_at)) &&
            ($this->three_dozen_disabled_end_at == NULL || Carbon::now()->lt($this->three_dozen_disabled_end_at));
    }

    public function tinsAreDisabled()
    {
        return $this->tins_disabled && 
            ($this->tins_disabled_start_at == NULL || Carbon::now()->gte($this->tins_disabled_start_at)) &&
            ($this->tins_disabled_end_at == NULL || Carbon::now()->lt($this->tins_disabled_end_at));
    }

    public function ribbonsAreDisabled()
    {
        return $this->ribbons_disabled && 
            ($this->ribbons_disabled_start_at == NULL || Carbon::now()->gte($this->ribbons_disabled_start_at)) &&
            ($this->ribbons_disabled_end_at == NULL || Carbon::now()->lt($this->ribbons_disabled_end_at));
    }

    public function glutenFreeIsDisabled()
    {
        return $this->gluten_free_disabled && 
            ($this->gluten_free_disabled_start_at == NULL || Carbon::now()->gte($this->gluten_free_disabled_start_at)) &&
            ($this->gluten_free_disabled_end_at == NULL || Carbon::now()->lt($this->gluten_free_disabled_end_at));
    }
}