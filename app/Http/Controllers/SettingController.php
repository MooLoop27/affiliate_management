<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'sg_commission_percentage' => 'required|numeric|min:0|max:100',
            'leader_commission_percentage' => 'required|numeric|min:0|max:100',
            'default_recipient_commission_percentage' => 'required|numeric|min:0|max:100',
            'system_theme' => 'required|in:light,dark',
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        foreach ($validated as $key => $value) {
            if ($key === 'company_logo') {
                if ($request->hasFile('company_logo')) {
                    $file = $request->file('company_logo');
                    $filename = 'logo.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('company', $filename, 'public');
                    Setting::setValue('company_logo', $path);
                }
                continue;
            }
            Setting::setValue($key, $value, 'general');
        }

        ActivityLog::log('Updated system settings');

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}

