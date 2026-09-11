<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class RegistrationPageController extends Controller
{
    public function index(): View
    {
        return view('frontend.register');
    }

    public function elderly(): View
    {
        return view('frontend.elderly-register');
    }

    public function volunteer(): View
    {
        return view('frontend.volunteer-register');
    }
}
