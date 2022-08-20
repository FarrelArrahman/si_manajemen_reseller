<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        $configuration = Configuration::configName('help');
        return view('help.index', compact('configuration'));
    }
}
