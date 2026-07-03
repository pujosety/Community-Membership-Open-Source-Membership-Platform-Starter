<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends ApiController
{
    public function index(Request $request)
    {
        $settings = Setting::all()->map(fn ($s) => [
            'key' => $s->key,
            'value' => $s->value,
            'group' => $s->group,
        ]);

        return $this->ok($settings);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|max:255',
            'settings.*.value' => 'nullable|string',
            'settings.*.group' => 'nullable|string|max:255',
        ]);

        $result = [];

        foreach ($data['settings'] as $item) {
            $result[] = Setting::updateOrCreate(
                ['key' => $item['key']],
                [
                    'value' => $item['value'] ?? null,
                    'group' => $item['group'] ?? null,
                ]
            );
        }

        return $this->ok($result, 'Saved');
    }
}
