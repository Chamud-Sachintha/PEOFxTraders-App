<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TotalUserEarning;
use App\Models\KYCDocument;
use App\Models\VerifyUser;
use App\Mail\VerifyEmail;
use App\Models\PackagePayment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\AppHelper;
use Session;
use Auth;
use Mail;
use Str;

class UsersController extends Controller
{
    public function showSigninPage() {
        return view('signin');
    }

    public function validateLogin(Request $request) {

        // $verify_login_again = User::where(['email' => $request->email])->first();

        // if ($verify_login_again->login_status != "0") {
        //     Session()->flash('status', 'You logged in Another Browser');
        //     return redirect('/signin');
        // }

        $helper = new AppHelper();
        $user = User::where(['username' => $request->email])->first();

        if ($user && Hash::check($request->password, $user->password)) {

            if ($user->email_verified_at == null) {
                Auth::logout();
                return redirect('/signin')->with(['error' => 'Please Verify your Email First.']);
            } else {
                Session()->put('member', $user);
                //User::where(['email' => $request->email])->update(['login_status' => '1']);

                if ($helper->isUserKYCApprovedOrNot($user->id)) {
                    return redirect('/app');
                } else {
                    Session()->flash('status', 'Please Submit Your KYC Documents');
                    return redirect('app/profile/kyc-info');
                }
            }
        } else {
            return redirect('/signin')->with(['error' => 'Invalid Email or Password']);
        }
    }

    public function showSignUpPage() {
        return view('signup');
    }

    public function createNewUser(Request $request) {

        $request->validate([
            'password' => 'required | min:8', 'fname' => 'required' , 'lname' => 'required',
            'username' => 'required', 'refNo' => 'required', 'country' => 'required',
            'mobile' => 'required', 'email' => 'required', 'address' => 'required',
            'state' => 'required', 'zipcode' => 'required', 'wtype' => 'required', 'wid' => 'required'
        ]);
        
        $data = [
            'subject' => 'Welcome to PeoFX Traders',
            'message' => 'Please verify Your Account Using Below Instructions.'
        ];

        if ($request->password !== $request->conf_password) {
            Session()->flash('status', 'Password Doesnt Match.');
            return redirect()->back();
        }

        $get_user_id = User::where(['refNo' => $request->refNo])->first();

        if ($get_user_id != null) {
            $find_package = DB::table('package_payments')->select('*')->join('users', 'package_payments.user_id', '=', 'users.id')
                                                        ->where('package_payments.user_id', '=', $get_user_id->id)
                                                        ->where('package_payments.status', '=', 'A')
                                                        ->get();       
        }
        
        // $userEmailVerify = User::where(['email' => $request->email])->first();

        // if ($userEmailVerify) {
        //     Session()->flash('status', 'Email Already Registered.');
        //     return redirect()->back();
        // }

        //dd($find_package);
        // if (count($find_package) == 0) {
        //     Session()->flash('status','This Referal Cannot Used at This time.');
        //     return redirect()->back();
        // }
        // ================ this is used for initial value for refferal ==============================================
        
        // $user = User::create([
        //     'fname' => $request->fname,
        //     'mname' => ($request->mname != null ? $request->mname : "-"),
        //     'lname' => $request->lname,
        //     'username' => $request->username,
        //     'refNo' => $request->username,
        //     'refNoCpy' => $request->refNo,
        //     'country' => $request->country,
        //     'mobile' => $request->mobile,
        //     'email' => $request->email,
        //     'address' => $request->address,
        //     'state' => $request->state,
        //     'gender' => $request->gender,
        //     'bdate' => $request->bdate,
        //     'wallet_type' => $request->wtype,
        //     'wallet_id' => $request->wid,
        //     'status' => 'A',
        //     'profile_image' => $request->profile_image,
        //     'password' => Hash::make($request->password),
        //     'login_status' => '0'
        // ]);

        // VerifyUser::create([
        //     'token' => Str::random(60),
        //     'user_id' => $user->id
        // ]);

        // Mail::to($user->email)->send(new VerifyEmail($user, $data));
        // return redirect()->route('verification-notice', ['user_id' => $user->id])->with('message', 'State saved correctly!!!');
        
        // ======================== end ========================================
        
        $checkUserReferelLimitCount = User::where(['refNoCpy' => $request->refNo])->count();
        $checkRefCpyAndOriginal = User::where(['refNo' => $request->refNo])->get();

        if ($checkUserReferelLimitCount >= 0 && $checkUserReferelLimitCount < 2 && !$checkRefCpyAndOriginal->isEmpty()) {

            $verifyUsername = User::where(['username' => $request->username])->first();
            // dd($verifyUsername);
            if ($verifyUsername != null) {
                do {
                    // have to create random number and concact to username
                    $rand_str = Str::random(5);
                    $checkkusername =  $request->username . $rand_str;
                    $newUsername = User::where(['username' => $checkkusername])->first();
                } while($newUsername != null);

                $msg = 'You Can Use ' . $checkkusername . ' ' .'for your Username.';
                Session()->flash('valid_username', $msg);
                return redirect()->back();
            }

            // do
            // {
            //     $referelCode = Str::random(5);
            //     $ref_code = User::where(['refNo' => $referelCode])->get();
            // }
            // while(!$ref_code->isEmpty());

            $user = User::create([
                'fname' => $request->fname,
                'mname' => ($request->mname != null ? $request->mname : "-"),
                'lname' => $request->lname,
                'username' => $request->username,
                'refNo' => $request->username,
                'refNoCpy' => $request->refNo,
                'country' => $request->country,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'address' => $request->address,
                'state' => $request->state,
                'gender' => $request->gender,
                'bdate' => $request->bdate,
                'wallet_type' => $request->wtype,
                'wallet_id' => $request->wid,
                'status' => 'A', // this sttus is used to active or inactive user by admin
                'profile_image' => $request->profile_image,
                'password' => Hash::make($request->password),
                'login_status' => '0'
            ]);
    
            VerifyUser::create([
                'token' => Str::random(60),
                'user_id' => $user->id
            ]);
    
            Mail::to($user->email)->send(new VerifyEmail($user, $data));
            return redirect()->route('verification-notice', ['user_id' => $user->id])->with('message', 'State saved correctly!!!');
        } else {
            Session()->flash('status', 'This Refeal No has it\'s Full Usage');
            return redirect()->back()->with(['error' => 'This Refeal No has it\'s Full Usage']);
        }
    }

    public function verifyEmail($token) {

        $verifiedUser = VerifyUser::where(['token' => $token])->first();

        if ($verifiedUser) {
            
            $user = $verifiedUser->user;
            if (!$user->email_verified_at) {
                $user->email_verified_at = Carbon::now();
                $user->save();

                return redirect('/signin')->with(['success' => 'Youre Email has been Verifies.']);
            } else {
                return redirect()->back()->with(['info' => 'Already been Verified.']);
            }

        } else {
            return redirect('/signin')->with(['error' => 'Something went Wrong']);
        }
    }

    public function showResendNoticePage() {
        return view('resend_verification_link');
    }

    public function resendVerificationLink(Request $user) {

        $data = [
            'subject' => 'Welcome to PeoFX Traders',
            'message' => 'Please verify Your Account Using Below Instructions.'
        ];
        $user_details = User::where(['id' => $user->user_id])->first();

        VerifyUser::where(['user_id' => $user->user_id])
                ->update([
                    'token' => Str::random(60)
                ]);
        
        Mail::to($user_details->email)->send(new VerifyEmail($user_details, $data));
        return redirect()->back()->with(['info' => 'Verification Link Resend Successfully.']);
    }

    public function showAllUsersDetailsPageForAdmin() {
        $users_details = User::all();
        return view('admin.all_users_details')->with(['users_details' => $users_details]);
    }

    public function getUsernamesListToAutocomplete(Request $request) {
        $query = $request->get('query');
        $filterResult = User::where('username', 'LIKE', '%'. $query. '%')->where('username', '!=', Session()->get('member')['username'])->pluck('username');
        return response()->json(json_decode($filterResult));
    }

    public function getUserActivatedPackageList(Request $request) {
        $query = $request->get('userId');
        $packages_list = DB::table('package_payments')->select('packages.package_amount', 'packages.rate')
                                                    ->join('packages', 'packages.id', '=', 'package_payments.package_id')
                                                    ->where('package_payments.user_id', '=', $query)
                                                    ->where('packages.status', '=', 'A')
                                                    ->get();
        
        return response()->json(json_decode($packages_list));
    }

    public function viewSelectedUserByUserId($user_id) {

        if (Session()->has('member')) {
            $user_details = User::where(['id' => $user_id])->first();
            $current_total = TotalUserEarning::where(['user_id' => $user_id])->first();
            $user_kyc_doc = KYCDocument::where(['user_id' => $user_id])->first();

            return view('admin.view_selected_user')->with(['user_details' => $user_details, 'current_total' => $current_total
                                                        ,   'user_kyc_docs' => $user_kyc_doc]);
        } else {
            Session()->flash('status', 'Access Denied.');
            return redirect()->back();
        }
    }

    public function signoutUser() {
        
        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];

            User::where(['id' => $user_id])->update(['login_status' => '0']);
            Auth::logout();
            Session::forget('member');
            
            return redirect('/');   
        } else {
            return redirect('/signin');
        }
    }
}
