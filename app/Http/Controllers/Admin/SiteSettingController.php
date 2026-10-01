<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::firstOrCreate(
            ['id' => 1],
            ['whatsapp_phone' => '8801333257604']
        );
        return view('admin.site-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_phone' => 'required|string|max:20',
        ]);

        $settings = SiteSetting::firstOrCreate(['id' => 1]);
        $settings->update([
            'whatsapp_phone' => $request->whatsapp_phone,
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
