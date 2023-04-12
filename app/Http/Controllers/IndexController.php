<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class IndexController extends Controller
{
    public function showWelcomePage() {

        $all_packages = Package::where(['status' => 'A'])->get();
        return view('welcome')->with(['packages' => $all_packages]);
    }

    public function showContactPageToUser() {
        return view('web.contact_us');
    }

    public function showAboutUsPageForUser() {
        return view('web.about');
    }

    public function showProjectPageForUser() {
        return view('web.project_plans');
    }
}
