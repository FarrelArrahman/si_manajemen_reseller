<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Menampilkan halaman bantuan.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil konten bantuan dari tabel konfigurasi
        $configuration = Configuration::configName('help');
        return view('help.index', compact('configuration'));
    }
}
