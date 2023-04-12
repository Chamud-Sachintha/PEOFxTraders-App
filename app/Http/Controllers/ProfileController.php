<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KYCDocument;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function showProfilePage() {
        $user_details = User::where(['id' => Session()->get('member')['id']])->first();

        return view('profile')->with(['user_profile_details' => $user_details]);
    }

    public function updateProfileInformations(Request $profileDetails) {
        /*
            have to put zip_code
        */

        $profileDetails->validate([
            'fname' => 'required', 'mname' => 'required', 'lname' => 'required',
            'country' => 'required', 'mobile' => 'required', 'email' => 'required | email',
            'address' => 'required', 'state' => 'required', 'gender' => 'required',
            'bdate' => 'required', 'wtype' => 'required', 'wid' => 'required',
        ]);

        User::where(['id' => $profileDetails->user_id])
                ->update([
                    'fname' => $profileDetails->fname, 'mname' => $profileDetails->mname, 'lname' => $profileDetails->lname,
                    'country' => $profileDetails->country, 'mobile' => $profileDetails->mobile, 'email' => $profileDetails->email,
                    'address' => $profileDetails->address, 'state' => $profileDetails->state,
                    'gender' => $profileDetails->gender, 'bdate' => $profileDetails->bdate, 'wallet_type' => $profileDetails->wtype,
                    'wallet_id' => $profileDetails->wid
                ]);

        return redirect()->back()->with(['success' => 'Profile Updated Successfully.']);
    }

    public function changePassword(Request $passwordDetails) {

        $passwordDetails->validate([
            'password' => ['required', 'min:6' ,'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'],
            'conf_password' => 'required'
        ]);

        if ($passwordDetails->password == $passwordDetails->conf_password) {
            User::where(['id' => $passwordDetails->user_id])
                    ->update([
                        'password' => Hash::make($passwordDetails->password)
                    ]);

            Session()->flash('status', 'Password is Updated.');
            return redirect()->back();
        } else {
            Session()->flash('status', 'Password is Not Matched.');
            return redirect()->back();
        }   
    }

    public function showKYCDocumentsUploadPage() {

        $user_id = Session()->get('member')['id'];
        $documents = KYCDocument::where(['user_id' => $user_id])->get();

        return view('kyc_info_page')->with(['documents' => $documents]);
    }

    public function submitKYCDocumentsToAdmin(Request $kycInformations) {

        /* 
            we have to check is there any kyc doc with same user id
            and if it is we have to update that query

            or if it is not we have to insert new query
        */

        $verify_user_docs = KYCDocument::where(['user_id' => $kycInformations->user_id, 'status' => 'A'])->first();

        if ($verify_user_docs != null) {
            Session()->flash('status', 'You\'ve Already Submit The Documents.');
            return redirect()->back();
        }

        $kycInformations->validate([
            'kyc_type' => 'required', 'f_image' => 'required',
            'b_image' => 'required',
        ]);

        $front_image = $kycInformations->f_image;
        $back_image = $kycInformations->b_image;
        $selfi_image = $kycInformations->selfi_image;

        $front_image = "data:image/png;base64,".base64_encode(file_get_contents($kycInformations->file('f_image')->path()));
        $back_image = "data:image/png;base64,".base64_encode(file_get_contents($kycInformations->file('b_image')->path()));
        $selfi_image = "data:image/png;base64,".base64_encode(file_get_contents($kycInformations->file('selfi_image')->path()));

        KYCDocument::create([
            'user_id' => $kycInformations->user_id,
            'kyc_type' => $kycInformations->kyc_type,
            'f_image' => $front_image,
            'b_image' => $back_image,
            'selfi_image' => $selfi_image,
            'status' => 'P',
            'reason' => "0"
        ]);

        Session()->flash('status', 'KYC Documents Uploaded Successfully.');
        return redirect()->back();
    }

    public function showReferralChainByChart() {

        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];
            $user = User::where(['id' => $user_id])->first();
        }

        $get_top_agent = User::where(['refNo' => $user->refNoCpy])->first();
        $pararal_agents = User::where(['refNoCpy' => $user->refNoCpy])->whereNotIn('id', [$user->id])->get();

        $level_one_below_agents = User::where(['refNoCpy' => $user->refNo])->get();

        $first = (empty($level_one_below_agents[0]) != true ? $level_one_below_agents[0]->username : "N/A");
        $second = (empty($level_one_below_agents[1]) != true ? $level_one_below_agents[1]->username : "N/A");

        $f_u_1 = "N/A";
        $f_u_2 = "N/A";
        
        $first_under_name = User::where(['username' => $first])->first();
        $first_under_users = User::where(['refNoCpy' => $first_under_name->refNo])->get();

        $f_u_1 = (empty($first_under_users[0]) != true ? $first_under_users[0]->username : "N/A");
        $f_u_2 = (empty($first_under_users[1]) != true ? $first_under_users[1]->username : "N/A");

        $s_u_1 = "N/A";
        $s_u_2 = "N/A";
        $second_under_name = User::where(['username' => $second])->first();
        if (!empty($second_under_name)) {
            $second_under_users = User::where(['refNoCpy' => $second_under_name->refNo])->get();   
        }

        $s_u_1 = (empty($second_under_users[0]) != true ? $second_under_users[0]->username : "N/A");
        $s_u_2 = (empty($second_under_users[1]) != true ? $second_under_users[1]->username : "N/A");

        return view('referral_chain_chart')->with(['top_level' => ($get_top_agent != null ? $get_top_agent->username : ""), 'pararal_agent' => (!empty($pararal_agents[0]) ? $pararal_agents[0] : "")
                                                ,   'user' => $user, 'first' => $first, 'second' => $second
                                                ,   'f_u_1' => $f_u_1, 'f_u_2' => $f_u_2,  's_u_1' => $s_u_1, 's_u_2' => $s_u_2]);
    }

    public function changeProfileAvatar(Request $profile_avatar) {
        //dd($profile_avatar);
        if ($profile_avatar != null) {

            $user_id = Session()->get('member')['id'];
            $update_profile_avatar = User::where(['id' => $user_id])
                                            ->update([
                                                'profile_image' => $profile_avatar->profile_image_change
                                            ]);
            
            $user = User::where(['id' => $user_id])->first();
            Session()->put('member', $user);
            Session()->flash('status', 'Profile Avatar Update Successfully.');
        } else {
            Session()->flash('status', 'Please Select Profile Avatar.');
        }

        return redirect()->back();
    }

    public function getDocRejectedReasonByUserId(Request $request_details) {

        $query = $request_details->get('userId');
        $reject_reason = KYCDocument::where(['user_id' => $query])->first();
        
        return response()->json(json_decode($reject_reason));
    }
}
