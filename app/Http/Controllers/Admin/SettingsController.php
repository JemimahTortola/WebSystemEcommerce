<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreInfo;
use App\Models\HeroEdit;
use App\Models\NotifInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $storeInfo = StoreInfo::firstOrCreate(['id' => 1], ['store_name' => 'TinyThreads']);
        $heroEdit = HeroEdit::firstOrCreate(['id' => 1], []);
        $notifInfo = NotifInfo::firstOrCreate(['id' => 1], []);

        return view('admin.settings.index', compact('storeInfo', 'heroEdit', 'notifInfo'));
    }

    public function update(Request $request)
    {
        $storeInfo = StoreInfo::firstOrCreate(['id' => 1], ['store_name' => 'TinyThreads']);
        $heroEdit = HeroEdit::firstOrCreate(['id' => 1], []);
        $notifInfo = NotifInfo::firstOrCreate(['id' => 1], []);

        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_email' => 'nullable|email',
            'store_phone' => 'nullable|string|max:20',
            'store_address' => 'nullable|string',
            'store_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'notification_email' => 'nullable|email',
        ]);

        $storeInfo->store_name = $request->store_name;
        $storeInfo->store_email = $request->store_email;
        $storeInfo->store_phone = $request->store_phone;
        $storeInfo->store_address = $request->store_address;
        $storeInfo->store_description = $request->store_description;

        if ($request->hasFile('logo')) {
            if ($storeInfo->logo) {
                Storage::disk('public')->delete('logos/' . $storeInfo->logo);
            }
            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->storeAs('logos', $logoName, 'public');
            $storeInfo->logo = $logoName;
        }
        $storeInfo->save();

        $heroEdit->hero_title = $request->hero_title;
        $heroEdit->hero_subtitle = $request->hero_subtitle;

        if ($request->hasFile('hero_image')) {
            if ($heroEdit->hero_image) {
                Storage::disk('public')->delete('hero/' . $heroEdit->hero_image);
            }
            $hero = $request->file('hero_image');
            $heroName = 'hero_' . time() . '.' . $hero->getClientOriginalExtension();
            $hero->storeAs('hero', $heroName, 'public');
            $heroEdit->hero_image = $heroName;
        }
        $heroEdit->save();

        $notifInfo->notif_new_order = $request->boolean('notif_new_order');
        $notifInfo->notif_low_stock = $request->boolean('notif_low_stock');
        $notifInfo->notif_out_of_stock = $request->boolean('notif_out_of_stock');
        $notifInfo->notif_new_review = $request->boolean('notif_new_review');
        $notifInfo->notif_new_customer = $request->boolean('notif_new_customer');
        $notifInfo->notif_weekly_report = $request->boolean('notif_weekly_report');
        $notifInfo->notification_email = $request->notification_email;
        $notifInfo->save();

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }
}
