<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Session;
use Auth;

class AdminController extends Controller
{
    public function showAdminSigninPage() {
        return view('admin.signin');
    }

    public function authenticateAdmin(Request $adminDetails) {

        $adminDetails->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $check_admin = Admin::where(['username' => $adminDetails->username])->first();

        if ($check_admin && Hash::check($adminDetails->password, $check_admin->password)) {
            Session()->put('member', $check_admin);
            return redirect('/admin/app');
        } else {
            Session()->flash('status', 'Invalid Username or Password');
            return redirect()->back();
        }
    }

    public function showAdminDashboard() {
        return view('admin.dashboard');
    }

    public function adminSignOut() {
        Auth::logout();
        Session::forget('member');
        
        return redirect('/');
    }
}
