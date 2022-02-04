<?php namespace App\Http\Controllers\Store;

use App\Models\Site;
use Illuminate\Http\Request;
use TakeoffDesignGroup\CMS\Http\Controllers\AdminBaseController;
use Carbon\Carbon;

class SettingsController extends AdminBaseController
{
    public function __construct()
    {
        view()->share('activePage', 'settings');
        
        parent::__construct();   
    }

    public function index()
    {
        $settings = Site::find(1);
        return view()->make('admin.store.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            ['choice_disabled_start_at' => 'date'],
            ['choice_disabled_end_at' => 'date'],
            ['tins_disabled_start_at' => 'date'],
            ['tins_disabled_end_at' => 'date'],
            ['ribbons_disabled_start_at' => 'date'],
            ['ribbons_disabled_end_at' => 'date'],
            ['gluten_free_disabled_start_at' => 'date'],
            ['gluten_free_disabled_end_at' => 'date'],
            ['two_dozen_disabled_start_at' => 'date'],
            ['two_dozen_disabled_end_at' => 'date'],
            ['three_dozen_disabled_start_at' => 'date'],
            ['three_dozen_disabled_end_at' => 'date']
        ]);
        
        $dateFields = [
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
        ];

        $settings = Site::find(1);
        $settings->fill($request->except($dateFields));
        $this->formatDates($settings, $dateFields);
        $this->clearDates($settings, ['choice', 'two_dozen', 'three_dozen', 'tins', 'ribbons', 'gluten_free']);
        $settings->save();

        // destroy saved settings
        session()->forget('settings');

        return redirect()->route('settings.index')
            ->with('success', 'Store settings have been successfully updated.');
    }

    private function formatDates($settings, $dates)
    {
        foreach ($dates as $key)
        {
            $data = request($key);
            $settings->{$key} = $data ? Carbon::parse($data) : NULL;
        }
    }

    private function clearDates($settings, $keys)
    {
        foreach ($keys as $key)
        {
            if (request("{$key}_disabled") == false) {
                $settings->{"{$key}_disabled_start_at"} = NULL;
                $settings->{"{$key}_disabled_end_at"} = NULL;
            }
        }
    }
}