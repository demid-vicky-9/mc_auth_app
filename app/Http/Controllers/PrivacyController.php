<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    /**
     * @return View
     */
    public function handle(): View
    {
        return view('privacy');
    }
}
