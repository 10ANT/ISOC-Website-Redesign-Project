<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', [
            'fields' => Setting::FIELDS,
            'values' => Setting::allCached(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = collect(Setting::FIELDS)
            ->flatMap(fn ($fields) => $fields)
            ->map(fn ($field) => match ($field[1]) {
                'url' => ['nullable', 'url', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'textarea' => ['nullable', 'string', 'max:2000'],
                default => ['nullable', 'string', 'max:255'],
            })
            ->all();

        Setting::putMany($request->validate($rules));

        return back()->with('success', 'Site settings saved.');
    }
}
