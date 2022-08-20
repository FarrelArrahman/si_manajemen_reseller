<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configuration = new Configuration;
        return view('configuration.index', compact('configuration'));
    }

    public function update(Request $request)
    {
        foreach($data = $request->except('auth_background_image') as $key => $value) {
            $config = Configuration::where('config_name', $key)->first();
            if($config && $config->value != $value) {
                $config->update([
                    'value' => $value
                ]);
            }
        }

        if($request->hasFile('auth_background_image')) {
            $config = Configuration::where('config_name', 'auth_background_image')->first();

            $request->file('auth_background_image')->storeAs('public', 'auth-bg.jpg');
        }

        return redirect()->route('configuration.index')->with('success', 'Berhasil mengubah konfigurasi.');
    }
}
