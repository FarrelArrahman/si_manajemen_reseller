<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
{
    /**
     * Menampilkan halaman konfigurasi.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil data konfigurasi
        $configuration = new Configuration;
        return view('configuration.index', compact('configuration'));
    }

    /**
     * Meng-update konfigurasi.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Lakukan perulangan dari data yang diinput, kecuali 'auth_background_image'
        foreach($data = $request->except('auth_background_image') as $key => $value) {
            // Update konfigurasi sesuai dengan jenisnya
            $config = Configuration::where('config_name', $key)->first();
            if($config && $config->value != $value) {
                $config->update([
                    'value' => $value
                ]);
            }
        }

        // Jika ada file background baru yang diupload
        if($request->hasFile('auth_background_image')) {
            // Ambil konfigurasi 'auth_background_image'
            $config = Configuration::where('config_name', 'auth_background_image')->first();
            // Simpan file background baru tersebut dengan nama auth-bg.jpg
            $request->file('auth_background_image')->storeAs('public', 'auth-bg.jpg');
        }

        // Setelah berhasil, alihkan kembali ke halaman konfigurasi
        // Dengan pesan "Berhasil mengubah konfigurasi"
        return redirect()->route('configuration.index')->with('success', 'Berhasil mengubah konfigurasi.');
    }
}
