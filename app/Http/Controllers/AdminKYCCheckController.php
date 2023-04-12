<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KYCDocument;
use App\Models\TotalUserEarning;
use App\Models\Package;
use App\Models\PackagePayment;

class AdminKYCCheckController extends Controller
{
    public function showSubmitedKYCDocumentsPage() {
        $kyc_details = DB::table('k_y_c_documents')->select('k_y_c_documents.kyc_type', 'k_y_c_documents.f_image', 'k_y_c_documents.b_image'
                                                    ,   'k_y_c_documents.selfi_image', 'k_y_c_documents.status', 'users.fname', 'users.mname'
                                                    ,   'users.lname', 'users.id as user_id')
                                                    ->join('users', 'users.id', '=', 'k_y_c_documents.user_id')
                                                    ->get();
        return view('admin.kyc_info_page')->with(['kyc_details' => $kyc_details]);
    }

    public function getKYCImagesByUserId(Request $request) {

        if ($request->has('userId')) {
            $user_id = $request->query('userId');
        }

        $kyc_docs = KYCDocument::where(['user_id' => $user_id])->first();

        return response()->json(['success'=>$kyc_docs]);
    }

    public function submitCheckingStatusToKYCDocuments(Request $kycDocDetails) {
        //dd($kycDocDetails);
        if ($kycDocDetails->status == "0") {
            Session()->flash('status', 'Please Select Valid Option.');
            return redirect()->back();
        }

        $verify_approved = KYCDocument::where(['user_id' => $kycDocDetails->user_id,'status' => 'A'])->first();

        if ($verify_approved != null) {
            Session()->flash('status', 'Already Approved That Documents.');
            return redirect()->back();
        }

        KYCDocument::where(['user_id' => $kycDocDetails->user_id])
                    ->update([
                        'status' => $kycDocDetails->status,
                        'reason' => ($kycDocDetails->reason != null ? $kycDocDetails->reason : "0")
                    ]); 

        if ($kycDocDetails->status == "A") {
        
            TotalUserEarning::create([
                'user_id' => $kycDocDetails->user_id,
                'total_earnings' => 0.00000000,
                'total_without_deduct' => 0.00000000,
                'earning_status' => 'I'
            ]);

            // $package_amount = Package::where(['package_name' => 'DEFAULT_PACKAGE'])->select('package_amount')->first();
            // $package_activate_request = PackagePayment::create([
            //     'user_id' => $kycDocDetails->user_id,
            //     'wallet_address' => 'DEFAULT_WALLET',
            //     'wallet_type' => 'DEFAULT_TYPE',
            //     'status' => 'A',
            //     'package_id' => 3,
            //     'amount_with_interest' => $package_amount->package_amount,
            //     'interest' => 0,
            //     'daily_int_amount' => 0,
            //     'txn_number' => 'DEFAULT_TXN',
            //     'earning_status' => 'P'
            // ]);

            Session()->flash('status', 'Document Approved Successfully.');
        } else {
            Session()->flash('status', 'Document Rejected Successfully.');
        }

        return redirect()->back();
    }
}
