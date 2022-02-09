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
        $data = Cache::remember('team', 3600, function () {
            $response = Http::withHeaders([
                'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSGF5ZGVuUm9jayIsImF1dGgiOiJkOTNsYWRmaGo5MSRmam0ifQ.r3M517FO5ezm4UGDV5zldOVEfQg7mBfEKqPzlUNLoak',
            ])->get('https://hookfast.dtpc.us/api/Expert/All');

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

        return view()->make('site._templates.team', compact('team', 'name'));
    }
}
