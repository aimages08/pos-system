<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'store_logo']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        // Handle logo upload
        if ($request->hasFile('store_logo')) {
            $path = $request->file('store_logo')->store('store', 'public');
            Setting::set('store_logo', $path);
        }

        return redirect('/settings')->with('success', 'Settings updated successfully.');
    }
}