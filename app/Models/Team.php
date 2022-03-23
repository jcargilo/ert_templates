<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use TakeoffDesignGroup\CMS\Models\Sites\Site;
use Cache;

class Team
{
    public $members;

    public function __construct(String $template)
    {
        $this->getTeamFromAPI($template);
    }

    protected function getTeamFromAPI(String $template) : void
    {
        $site = cache('site') ?? Site::find(1);
        
        if ((!$site->cache_data ?? false) || auth()->check()) {
            Cache::forget('team');
        }

        $data = Cache::remember('team', 3600, function () use ($site) {
            $domain = $site->attributes['biz_domain'] ?? 'https://biz-diagnostic.com';

            $response = Http::withHeaders([
                'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSGF5ZGVuUm9jayIsImF1dGgiOiJkOTNsYWRmaGo5MSRmam0ifQ.r3M517FO5ezm4UGDV5zldOVEfQg7mBfEKqPzlUNLoak',
            ])->get("{$domain}/api/Expert/All");

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        });

        switch ($template) {
            case 'team_ppt':
                $this->members = $data['PPTExperts'] ?? [];
                break;
            case 'team_vfo':
                $this->members = $data['VFOExperts'] ?? [];
                break;
            default:
                $this->members = $data['LAAExperts'] ?? [];
        }

        // Convert line breaks
        foreach ($this->members as $key => $member) {
            if (isset($this->members[$key]['LongBio'])) {
                $this->members[$key]['LongBio'] = nl2br($this->members[$key]['LongBio']);
            }
        }
    }
}