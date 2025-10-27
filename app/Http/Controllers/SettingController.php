<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::first();
        return view('backend.settings.index',compact('setting'));

    }


    public function update(Request $request, Setting $setting)
    {
        $data = $request->validate([
            'bname'                 => 'required|string|max:200',
            'email'                 => 'nullable|email|max:200',
            'phone'                 => 'nullable|string|max:20',
            'currency'               => 'nullable|string|max:20',
            'whatsapp'              => 'nullable|string|max:20',
            'address'               => 'nullable|string|max:255',
            'logo'                  => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048',
            'meta_title'            => 'nullable|string|max:255',
            'meta_keywords'         => 'nullable|string',
            'meta_description'      => 'nullable|string',
            'social'                => 'nullable',
            'map'                   => 'nullable',
            'header'                => 'nullable',
            'footer'                => 'nullable',
            'other'                 => 'nullable',
        ]);

        //dd($data);

        if($request->file('logo'))
        {
			//create unique name of image
            $logoName = time().'.'.$request->logo->getClientOriginalExtension();
			//move image to path you wish -- it auto generate folder
            $request->logo->move(public_path('uploads/images/logo/'), $logoName);
            $data['logo'] = $logoName;
        }

        $setting->update($data);
        return redirect()->route('setting')->with('success', 'Settings Updated Successfully!');
    }


}
