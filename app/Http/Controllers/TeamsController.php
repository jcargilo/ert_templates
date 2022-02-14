<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Cache;

class TeamsController extends SiteBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        switch (request()->segment(2)) {
            case 'proactive-planning':
                $team = $data['PPTExperts'] ?? [];
                $name = 'Proactive Planning Team';
                break;
            case 'virtual-family-office':
                $team = $data['VFOExperts'] ?? [];
                $name = 'Virtual Family Office';
                break;
            default:
                $team = [];
                $name = 'Accounting Services Team';
        }

        // Convert line breaks
        foreach ($team as $key => $member) {
            if (isset($team[$key]['LongBio'])) {
                $team[$key]['LongBio'] = nl2br($team[$key]['LongBio']);
            }
        }

        return view()->make('site._templates.team', compact('team', 'name'));
    }
}
