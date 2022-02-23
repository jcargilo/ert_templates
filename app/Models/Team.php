<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Cache;

class Team
{
    public $name;
    public $members;

    public function __construct(String $template)
    {
        $this->getTeamFromAPI($template);
    }

    protected function getTeamFromAPI(String $template) : void
    {
        if (auth()->check()) {
            Cache::forget('team');
        }

        $data = Cache::remember('team', 3600, function () {
            $response = Http::withHeaders([
                'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSGF5ZGVuUm9jayIsImF1dGgiOiJkOTNsYWRmaGo5MSRmam0ifQ.r3M517FO5ezm4UGDV5zldOVEfQg7mBfEKqPzlUNLoak',
            ])->get('https://ffo.biz-diagnostic.com/api/Expert/All');

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        });

        switch ($template) {
            case 'team_accounting':
                $this->members = $data['LAAExperts'] ?? [];
                $this->name = 'Accounting Services Team';
                break;
            case 'team_ppt':
                $this->members = $data['PPTExperts'] ?? [];
                $this->name = 'Proactive Planning Team';
                break;
            case 'team_vfo':
                $this->members = $data['VFOExperts'] ?? [];
                $this->name = 'Virtual Family Office';
                break;
        }

        // Convert line breaks
        foreach ($this->members as $key => $member) {
            if (isset($this->members[$key]['LongBio'])) {
                $this->members[$key]['LongBio'] = nl2br($this->members[$key]['LongBio']);
            }
        }
    }
}