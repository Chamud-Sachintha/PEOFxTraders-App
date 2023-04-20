<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Package;
use App\Models\PackagePayment;
use App\Models\Deposit;
use App\Models\TotalUserEarning;
use App\Models\SharePackage;
use App\Models\User;
use App\Models\ReferalComission;
use App\Models\ReferalComissionLog;
use App\Models\ReferalPEOValueComission;
use App\Models\DailyPEOValueUSDTLog;
use App\Helpers\AppHelper;

class PackageController extends Controller
{
    public function showActivePackagesPage() {

        $helper = new AppHelper();

        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];

            $package_details = Package::where(['status' => 'A'])->get();
            $user_active_packages = DB::table('package_payments')->select('packages.package_name', 'packages.rate', 'packages.package_amount', 'packages.peo_value'
                                                                        ,   'package_payments.status')
                                                                ->join('packages', 'packages.id', '=', 'package_payments.package_id')
                                                                ->where('package_payments.user_id', '=', $user_id)
                                                                ->where('packages.status', '=', 'A')
                                                                ->get();

            if ($helper->isUserKYCApprovedOrNot($user_id)) {
                return view('active_packages')->with(['package_details' => $package_details, 'user_packages' => $user_active_packages]);
            } else {
                Session()->flash('status', 'Please Submit Your KYC Documents.');
                return redirect('app/profile/kyc-info');
            }   
        } else {
            Session()->flash('status', 'Access Denied.');
            return redirect('/signin');
        }
    }

    public function showActivatePackageAndDepositPage($package_id) {
        return view('activate_package_by_payment')->with(['package_id' => $package_id]);
    }

    public function activePackageAndDeposit(Request $packageDetails) {

        $packageDetails->validate([
            'txn_number' => 'required',
        ]);

        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];
        }

        $package_amount = Package::where(['id' => $packageDetails->package_id])->select('package_amount')->first();
        $currentPackageDetails = Package::where(['id' => $packageDetails->package_id])->first();

        $get_wallet_address = User::where(['id' => $user_id])->first();

        $package_activate_request = PackagePayment::create([
            'user_id' => $user_id,
            'wallet_address' => $get_wallet_address->wallet_id,
            'wallet_type' => $packageDetails->wtype,
            'status' => 'P',
            'package_id' => $packageDetails->package_id,
            'amount_with_interest' => $package_amount->package_amount,
            'interest' => 0,
            'daily_int_amount' => ($package_amount->package_amount * ($currentPackageDetails->rate / 100)),
            'txn_number' => $packageDetails->txn_number,
            'earning_status' => 'P'
        ]);

        if ($package_activate_request) {
            Session()->flash('status', 'Package is Pending Request is Done.');
        } else {
            Session()->flash('status', 'There is an Error Occur.');
        }

        return redirect('/app/active-packages');
    }

    public function showPackageRequestsForAdmin() {

        $package_details = DB::table('package_payments')->select('package_payments.id as uid','package_payments.user_id as user_id', 'package_payments.package_id as package_id', 'packages.package_name', 'package_payments.wallet_type', 'packages.package_amount'
                                                    ,   'package_payments.status', 'users.fname', 'users.mname', 'users.lname', 'package_payments.created_at'
                                                    ,   'package_payments.txn_number')
                                                    ->join('users', 'users.id', '=', 'package_payments.user_id')
                                                    ->join('packages', 'packages.id', '=', 'package_payments.package_id')
                                                    ->get();
        return view('admin.package_request')->with(['package_details' => $package_details]);
    }

    public function approveOrRejectPackageRequestByAdmin(Request $packageUpdateRequest) {
        $helper = new AppHelper();

        $verifyPackageStatus = PackagePayment::where(['user_id' => $packageUpdateRequest->user_id, 'id' => $packageUpdateRequest->uid])->first();

        if ($verifyPackageStatus->status == "A") {
            Session()->flash('status', 'This package is Already Activated.');
            return redirect()->back();
        }

        if ($verifyPackageStatus->status == "R") {
            PackagePayment::where(['user_id' => $packageUpdateRequest->user_id, 'id' => $packageUpdateRequest->uid])
                    ->update([
                        'status' => $packageUpdateRequest->status,
                        'earning_status' => 'R'
                    ]);

            Session()->flash('status', 'Package Rejected Successfully.');
            return redirect()->back();
        }

        if ($packageUpdateRequest->status == "0") {
            return redirect()->back();
        } else {
            $updateSuccess = PackagePayment::where(['user_id' => $packageUpdateRequest->user_id, 'id' => $packageUpdateRequest->uid])
                    ->update([
                        'status' => $packageUpdateRequest->status,
                        'earning_status' => 'H'
                    ]);

            if ($updateSuccess) {
                PackagePayment::where(['user_id' => $packageUpdateRequest->user_id, 'package_id' => '3'])
                                ->delete();
            }

            if ($packageUpdateRequest->status == "A") {
                TotalUserEarning::where(['user_id' => $packageUpdateRequest->user_id])
                                    ->update(['earning_status' => 'A']);
            }
        }

        // get first level agents

        $getUserRefferalDetails = User::where(['id' => $packageUpdateRequest->user_id])->select('refNo', 'refNoCpy')->get();;
        $getPrimaryLevelAgent = User::where(['refNo' => $getUserRefferalDetails[0]->refNoCpy])->get();

        // get secondary agents
        $getSecondaryLevelAgent = [];
        if (count($getPrimaryLevelAgent) != 0) {
            $getSecondaryLevelAgent = User::where(['refNo' => $getPrimaryLevelAgent[0]->refNoCpy])->get();
        }

        $package_details = Package::where(['id' => $packageUpdateRequest->package_id])->first();

        $total = 0;
        if (count($getPrimaryLevelAgent) != 0) {
            $primary = TotalUserEarning::where(['user_id' => $getPrimaryLevelAgent[0]->id])->first();

            $check_earning_status = TotalUserEarning::where(['user_id' => $getPrimaryLevelAgent[0]->id])->first();

            if ($check_earning_status->earning_status == "A") {

                // $refComTable = new ReferalComission();

                // $refComTable->from_user_id = $packageUpdateRequest->user_id;
                // $refComTable->to_user_id = $getPrimaryLevelAgent[0]->id;
                // $refComTable->rate = "5";
                // $refComTable->amount = ($package_details->package_amount * 0.05);
                // $refComTable->time = $helper->getDayTime();
                // $refComTable->status = "A";

                // $refComTable->save();

                $getRefUid = ReferalComission::create([
                    'from_user_id' => $packageUpdateRequest->user_id,
                    'to_user_id' => $getPrimaryLevelAgent[0]->id,
                    'rate' => '5',
                    'amount' => ($package_details->package_amount * 0.05),
                    'time' => $helper->getDayTime(),
                    'status' => 'A'
                ]);
                //dd($getRefUid->id);
                $total = $package_details->package_amount * 0.05;
    
                $getSumOfComission = ReferalComission::where(['to_user_id' =>$getPrimaryLevelAgent[0]->id, 'time' => $helper->getDayTime(), 'status' => 'A'])->sum('amount');
    
                TotalUserEarning::where(['user_id' => $getPrimaryLevelAgent[0]->id])
                                ->update([
                                    'total_earnings' => $primary->total_earnings + $getSumOfComission,
                                    'total_without_deduct' => $primary->total_without_deduct + $getSumOfComission
                                ]);                
                
                ReferalComission::where(['id' => $getRefUid->id])->update(['status' => 'I']);
            }
        }

        if (count($getSecondaryLevelAgent) != 0) {
            $secondary = TotalUserEarning::where(['user_id' => $getSecondaryLevelAgent[0]->id])->first();

            $check_earning_status_two = TotalUserEarning::where(['user_id' => $getSecondaryLevelAgent[0]->id])->first();
            
            if ($check_earning_status_two->earning_status == "A") {

                $getRefId = ReferalComission::create([
                    'from_user_id' => $packageUpdateRequest->user_id,
                    'to_user_id' => $getSecondaryLevelAgent[0]->id,
                    'rate' => '2',
                    'amount' => ($package_details->package_amount * 0.02),
                    'time' => $helper->getDayTime(),
                    'status' => 'A'
                ]);
    
                $total = $package_details->package_amount * 0.02;
                $getSumOfComission = ReferalComission::where(['to_user_id' =>$getSecondaryLevelAgent[0]->id, 'time' => $helper->getDayTime(), 'status' => 'A'])->sum('amount');
    
                TotalUserEarning::where(['user_id' => $getSecondaryLevelAgent[0]->id])
                                ->update([
                                    'total_earnings' => $helper->getUserCurrentEarning($getSecondaryLevelAgent[0]->id) + $getSumOfComission,
                                    'total_without_deduct' => $helper->getTotalWithoutDeduct($getSecondaryLevelAgent[0]->id) + $getSumOfComission
                                ]);
                
                ReferalComission::where(['id' => $getRefId->id])->update(['status' => 'I']);
            }
        }

        $getSelectedUser = User::where(['id' => $packageUpdateRequest->user_id])->first();
        $refCpyTmp = $getSelectedUser->refNoCpy;
        $tmpCount = 1;

        do {

            $getAgent = User::where(['refNo' => $refCpyTmp])->first();

            if ($getAgent != null) {

                $getComissionLog = ReferalComissionLog::where(['user_id' => $getAgent->id])->first();

                $check_earning_status_three = TotalUserEarning::where(['user_id' => $getAgent->id])->first();

                if ($check_earning_status_three->earning_status == "A") {
                    if ($tmpCount == 1) {
                        ReferalPEOValueComission::create([
                            'from_user_id' => $packageUpdateRequest->user_id,
                            'to_user_id' => $getAgent->id,
                            'peo_value' => ($package_details->peo_value),
                            'time' => $helper->getDayTime()
                        ]);

                        // ReferalPEOValueComission::create([
                        //     'from_user_id' => $packageUpdateRequest->user_id,
                        //     'to_user_id' => $getAgent->id,
                        //     'peo_value' => 0,
                        //     'time' => $helper->getDayTime()
                        // ]);
    
                        if ($getComissionLog != null) {
                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                                ->update([
                                                    'peo_value' => ($getComissionLog->peo_value + $package_details->peo_value)
                                                ]);
                            // ReferalComissionLog::where(['user_id' => $getAgent->id])
                            //                     ->update([
                            //                         'peo_value' => 0
                            //                     ]);
                        } else {
                            ReferalComissionLog::create([
                                'user_id' => $getAgent->id,
                                'peo_value' => ($package_details->peo_value),
                            ]);
                            // ReferalComissionLog::create([
                            //     'user_id' => $getAgent->id,
                            //     'peo_value' => 0,
                            // ]);
                        }
                    } else {
                        // ReferalPEOValueComission::create([
                        //     'from_user_id' => $packageUpdateRequest->user_id,
                        //     'to_user_id' => $getAgent->id,
                        //     'peo_value' => ($package_details->peo_value / 2),
                        //     'time' => $helper->getDayTime()
                        // ]);

                        ReferalPEOValueComission::create([
                            'from_user_id' => $packageUpdateRequest->user_id,
                            'to_user_id' => $getAgent->id,
                            'peo_value' => ($package_details->peo_value),
                            'time' => $helper->getDayTime()
                        ]);

                        // ReferalPEOValueComission::create([
                        //     'from_user_id' => $packageUpdateRequest->user_id,
                        //     'to_user_id' => $getAgent->id,
                        //     'peo_value' => 0,
                        //     'time' => $helper->getDayTime()
                        // ]);
    
                        if ($getComissionLog != null) {
                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                                ->update([
                                                    'peo_value' => ($getComissionLog->peo_value + ($package_details->peo_value))
                                                ]);
                            // ReferalComissionLog::where(['user_id' => $getAgent->id])
                            //                         ->update([
                            //                             'peo_value' => 0
                            //                         ]);
                        } else {
                            // ReferalComissionLog::create([
                            //     'user_id' => $getAgent->id,
                            //     'peo_value' => ($package_details->peo_value / 2),
                            // ]);

                            ReferalComissionLog::create([
                                'user_id' => $getAgent->id,
                                'peo_value' => ($package_details->peo_value),
                            ]);
                            
                        }
                    }
    
                    $myUserdataList = User::where(['id' =>  $getAgent->id])->first();
                    $myReferralsList = User::where(['refNoCpy' => $myUserdataList->refNo])->get();
                    $getLeftSidePEOValueAmt = 0;
                    $getRightSidePEOValueAmt = 0;

                    if (!empty($myReferralsList[0])) {
                        $getLeftSidePEOValueAmt = ReferalPEOValueComission::where(['from_user_id' => $myReferralsList[0]->id])->sum('peo_value');
                    }

                    if (!empty($myReferralsList[1])) {
                        $getRightSidePEOValueAmt = ReferalPEOValueComission::where(['from_user_id' => $myReferralsList[1]->id])->sum('peo_value');
                    }

                    $PEOtoUSD = 0;
                    $remainPEOValue = 0;

                    // ==== new ref peo value comission algorithm ====

                    // if ($getLeftSidePEOValueAmt >= 2 && $getRightSidePEOValueAmt >= 2) {
                    //     if ($getLeftSidePEOValueAmt < $getRightSidePEOValueAmt) {
                    //         $PEOtoUSD = ($getLeftSidePEOValueAmt/2) * 3;
                    //         $remainPEOValue = $getRightSidePEOValueAmt - $getLeftSidePEOValueAmt;
                    //     } else if ($getRightSidePEOValueAmt < $getLeftSidePEOValueAmt) {
                    //         $PEOtoUSD = ($getRightSidePEOValueAmt/2) * 3;
                    //         $remainPEOValue = $getLeftSidePEOValueAmt - $getRightSidePEOValueAmt;
                    //     } else if ($getRightSidePEOValueAmt == $getLeftSidePEOValueAmt) {
                    //         $PEOtoUSD = ($getRightSidePEOValueAmt / 2) * 3;
                    //         $remainPEOValue = $getLeftSidePEOValueAmt - $getRightSidePEOValueAmt;
                    //     } else {
                    //         // nothing
                    //     }
                        
                    //     $saveUSDT = DailyPEOValueUSDTLog::create([
                    //         'user_id' => $getAgent->id,
                    //         'amount' => $PEOtoUSD,
                    //         'time' => $helper->getDayTime(),
                    //         'status' => 'A'
                    //     ]);
                        
                    //     $getUSDTById = DailyPEOValueUSDTLog::where(['id' => $saveUSDT->id, 'status' => 'A'])->first();
    
                    //     TotalUserEarning::where(['user_id' => $getAgent->id])
                    //                                 ->update([
                    //                                     'total_earnings' => $helper->getUserCurrentEarning($getAgent->id) + $getUSDTById->amount,
                    //                                     'total_without_deduct' => $helper->getUserCurrentEarning($getAgent->id) + $getUSDTById->amount
                    //                                 ]);
        
                    //     // ReferalComissionLog::where(['user_id' => $getAgent->id])
                    //     //                         ->update([
                    //     //                             'peo_value' => $remainPEOValue
                    //     //                         ]);

                    //     ReferalComissionLog::where(['user_id' => $getAgent->id])
                    //                             ->update([
                    //                                 'peo_value' => 0
                    //                             ]);
                                                
                        
                    //     DailyPEOValueUSDTLog::where(['id' => $saveUSDT->id])->update(['status' => 'I']);
                    // }

                    // === end of new ref comission algorithm ===

                    $get_agent_active_total = DB::table('packages')->select(DB::raw("SUM(packages.package_amount) as total"))
                                                                    ->join('package_payments', 'packages.id', '=', 'package_payments.package_id')
                                                                    ->where('package_payments.status', '=', 'A')
                                                                    ->where('package_payments.user_id', '=', $getAgent->id)
                                                                    ->get();

                    //dd($get_agent_active_total[0]->total);

                    $current_agent_total = TotalUserEarning::where(['user_id' => $getAgent->id])->first();
                    $current_peo_value_agent = ReferalComissionLog::where(['user_id' => $getAgent->id])->first();

                    if ($package_details->peo_value == 1) {
                        if ($get_agent_active_total[0]->total >= 50) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 2,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                                ->update([
                                                    'total_earnings' => ($current_agent_total->total_earnings + 2),
                                                    'total_without_deduct' => ($current_agent_total->total_without_deduct + 2)
                                                ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                                ->update([
                                                    'peo_value' => (($current_peo_value_agent->peo_value - 1) < 0 ? 0 : $current_peo_value_agent->peo_value - 1)
                                                ]);
                        }
                    }else if ($package_details->peo_value == 2) {
                        if ($get_agent_active_total[0]->total >= 100) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 3,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                    ->update([
                                        'total_earnings' => ($current_agent_total->total_earnings + 3),
                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 3)
                                    ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                    ->update([
                                        'peo_value' => (($current_peo_value_agent->peo_value - 2) < 0 ? 0 : ($current_peo_value_agent->peo_value - 2))
                                    ]);
                        }
                    } else if ($package_details->peo_value == 3) {
                        if ($get_agent_active_total[0]->total >= 150) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 4,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                    ->update([
                                        'total_earnings' => ($current_agent_total->total_earnings + 4),
                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 4)
                                    ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                    ->update([
                                        'peo_value' => (($current_peo_value_agent->peo_value - 3) < 0 ? 0 : ($current_peo_value_agent->peo_value - 3))
                                    ]);
                        }
                    } else if ($package_details->peo_value == 4) {
                        if ($get_agent_active_total[0]->total >= 200) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 5,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                    ->update([
                                        'total_earnings' => ($current_agent_total->total_earnings + 5),
                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 5)
                                    ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                    ->update([
                                        'peo_value' => (($current_peo_value_agent->peo_value - 4) < 0 ? 0 : ($current_peo_value_agent->peo_value - 4))
                                    ]);
                        }
                    } else if ($package_details->peo_value == 5) {
                        if ($get_agent_active_total[0]->total >= 250) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 6,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                    ->update([
                                        'total_earnings' => ($current_agent_total->total_earnings + 6),
                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 6)
                                    ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                    ->update([
                                        'peo_value' => (($current_peo_value_agent->peo_value - 5) < 0 ? 0 : ($current_peo_value_agent->peo_value - 5))
                                    ]);
                        }
                    } else if ($package_details->peo_value == 10) {
                        if ($get_agent_active_total[0]->total >= 500) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 12,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                    ->update([
                                        'total_earnings' => ($current_agent_total->total_earnings + 12),
                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 12)
                                    ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                    ->update([
                                        'peo_value' => (($current_peo_value_agent->peo_value - 10) < 0 ? 0 : ($current_peo_value_agent->peo_value - 10))
                                    ]);
                        }
                    }else if ($package_details->peo_value == 20) {
                        if ($get_agent_active_total[0]->total >= 1000) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 24,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                    ->update([
                                        'total_earnings' => ($current_agent_total->total_earnings + 24),
                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 24)
                                    ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                    ->update([
                                        'peo_value' => (($current_peo_value_agent->peo_value - 20) < 0 ? 0 : ($current_peo_value_agent->peo_value - 20))
                                    ]);
                        }
                    }else if ($package_details->peo_value == 40) {
                        if ($get_agent_active_total[0]->total >= 2000) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 48,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                    ->update([
                                        'total_earnings' => ($current_agent_total->total_earnings + 48),
                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 48)
                                    ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                    ->update([
                                        'peo_value' => (($current_peo_value_agent->peo_value - 40) < 0 ? 0 : ($current_peo_value_agent->peo_value - 40))
                                    ]);
                        }
                    }else if ($package_details->peo_value == 80) {
                        if ($get_agent_active_total[0]->total >= 4000) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 96,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                    ->update([
                                        'total_earnings' => ($current_agent_total->total_earnings + 96),
                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 96)
                                    ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                    ->update([
                                        'peo_value' => (($current_peo_value_agent->peo_value - 80) < 0 ? 0 : ($current_peo_value_agent->peo_value - 80))
                                    ]);
                        }
                    }else if ($package_details->peo_value == 160) {
                        if ($get_agent_active_total[0]->total >= 8000) {
                            $saveUSDT = DailyPEOValueUSDTLog::create([
                                        'user_id' => $getAgent->id,
                                        'amount' => 192,
                                        'time' => $helper->getDayTime(),
                                        'status' => 'A'
                                    ]);

                            TotalUserEarning::where(['user_id' => $getAgent->id])   
                                    ->update([
                                        'total_earnings' => ($current_agent_total->total_earnings + 192),
                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 192)
                                    ]);

                            ReferalComissionLog::where(['user_id' => $getAgent->id])
                                    ->update([
                                        'peo_value' => (($current_peo_value_agent->peo_value - 160) < 0 ? 0 : ($current_peo_value_agent->peo_value - 160))
                                    ]);
                        }
                    }
                }

                $refCpyTmp = $getAgent->refNoCpy;
                $tmpCount += 1;
            } else {
                break;
            }
        }
        while(true);

        Session()->flash('status', 'Package Request Update Successfully.');
        return redirect()->back();
    }

    public function showSharePackagePageForUser() {

        $helper = new AppHelper();

        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];

            $users_list = User::all()->except($user_id);
            $getCurrentTotalUserEarning = TotalUserEarning::where(['user_id' => $user_id])->sum('total_earnings');
            $packages = DB::table('packages')->select('*')->where('status', '=', 'A')->whereBetween('package_amount', [0, $getCurrentTotalUserEarning])->get();
            $getSharePackagehistory = DB::table('share_packages')->select('share_packages.id', 'packages.package_name','packages.package_amount'
                                                                        ,   'share_packages.created_at', 'users.username')
                                                                ->join('users', 'users.id', '=', 'share_packages.user_to_id')
                                                                ->join('packages', 'packages.id', '=', 'share_packages.package_id')
                                                                ->where('share_packages.user_from_id', '=', $user_id)
                                                                ->get();

            if ($helper->isUserKYCApprovedOrNot($user_id)) {
                return view('share_package')->with(['total_earning' => $getCurrentTotalUserEarning
                                        ,   'users_list' => $users_list
                                        ,   'packages' => $packages
                                        ,   'share_history' => $getSharePackagehistory]);
            } else {
                Session()->flash('status', 'Please Submit your KYC Documents.');
                return redirect('/app/profile/kyc-info');
            }
        } else {
            Session()->flash('status', 'Access Denied.');
            return redirect('/signin');
        }
    }

    public function makeSharePackageForSpecificUser(Request $shareDetails) {
        $helper = new AppHelper();

        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];
        }

        $shareNewPackageToUser = SharePackage::create([
            'user_from_id' => $user_id,
            'user_to_id' => $helper->getUserIdByUsername($shareDetails->transfer_to_id),
            'package_id' => $shareDetails->package_id,
        ]);

        if ($shareNewPackageToUser) {
            $getCurrentTotalUserEarning = TotalUserEarning::where(['user_id' => $user_id])->sum('total_earnings');
            $deductPackageAmountFromEarning = TotalUserEarning::where(['user_id' => $user_id])
                                                                ->update([
                                                                    'total_earnings' => ($getCurrentTotalUserEarning - $helper->getPackageAmountByPackageId($shareDetails->package_id))
                                                                ]);

            $package_activate_request = PackagePayment::create([
                'user_id' => $helper->getUserIdByUsername($shareDetails->transfer_to_id),
                'wallet_address' => $helper->getWalletInformationsByUserId($helper->getUserIdByUsername($shareDetails->transfer_to_id))->wallet_id,
                'wallet_type' => $helper->getWalletInformationsByUserId($helper->getUserIdByUsername($shareDetails->transfer_to_id))->wallet_type,
                'status' => 'A',
                'package_id' => $shareDetails->package_id,
                'amount_with_interest' => $helper->getPackageAmountByPackageId($shareDetails->package_id),
                'interest' => 0,
                'daily_int_amount' => ($helper->getPackageAmountByPackageId($shareDetails->package_id) * ($helper->getPackageDetailsByPackageId($shareDetails->package_id)->rate / 100)),
                'txn_number' => 'N/A',
                'earning_status' => 'H'
            ]);

            TotalUserEarning::where(['user_id' => $helper->getUserIdByUsername($shareDetails->transfer_to_id)])
                                    ->update(['earning_status' => 'A']);

            $getUserRefferalDetails = User::where(['id' => $helper->getUserIdByUsername($shareDetails->transfer_to_id)])->select('refNo', 'refNoCpy')->get();;
            $getPrimaryLevelAgent = User::where(['refNo' => $getUserRefferalDetails[0]->refNoCpy])->get();

            // get secondary agents
            $getSecondaryLevelAgent = [];
            if (count($getPrimaryLevelAgent) != 0) {
                $getSecondaryLevelAgent = User::where(['refNo' => $getPrimaryLevelAgent[0]->refNoCpy])->get();
            }

            $package_details = Package::where(['id' => $shareDetails->package_id])->first();

            $total = 0;
            if (count($getPrimaryLevelAgent) != 0) {
                $primary = TotalUserEarning::where(['user_id' => $getPrimaryLevelAgent[0]->id])->first();

                $check_earning_status = TotalUserEarning::where(['user_id' => $getPrimaryLevelAgent[0]->id])->first();

                if ($check_earning_status->earning_status == "A") {

                    $getRefUid = ReferalComission::create([
                        'from_user_id' => $helper->getUserIdByUsername($shareDetails->transfer_to_id),
                        'to_user_id' => $getPrimaryLevelAgent[0]->id,
                        'rate' => '5',
                        'amount' => ($package_details->package_amount * 0.05),
                        'time' => $helper->getDayTime(),
                        'status' => 'A'
                    ]);
                    //dd($getRefUid->id);
                    $total = $package_details->package_amount * 0.05;
        
                    $getSumOfComission = ReferalComission::where(['to_user_id' =>$getPrimaryLevelAgent[0]->id, 'time' => $helper->getDayTime(), 'status' => 'A'])->sum('amount');
        
                    TotalUserEarning::where(['user_id' => $getPrimaryLevelAgent[0]->id])
                                    ->update([
                                        'total_earnings' => $primary->total_earnings + $getSumOfComission,
                                        'total_without_deduct' => $primary->total_without_deduct + $getSumOfComission
                                    ]);                
                    
                    ReferalComission::where(['id' => $getRefUid->id])->update(['status' => 'I']);
                }
            }

            if (count($getSecondaryLevelAgent) != 0) {
                $secondary = TotalUserEarning::where(['user_id' => $getSecondaryLevelAgent[0]->id])->first();

                $check_earning_status_two = TotalUserEarning::where(['user_id' => $getSecondaryLevelAgent[0]->id])->first();
                
                if ($check_earning_status_two->earning_status == "A") {

                    $getRefId = ReferalComission::create([
                        'from_user_id' => $helper->getUserIdByUsername($shareDetails->transfer_to_id),
                        'to_user_id' => $getSecondaryLevelAgent[0]->id,
                        'rate' => '2',
                        'amount' => ($package_details->package_amount * 0.02),
                        'time' => $helper->getDayTime(),
                        'status' => 'A'
                    ]);
        
                    $total = $package_details->package_amount * 0.02;
                    $getSumOfComission = ReferalComission::where(['to_user_id' =>$getSecondaryLevelAgent[0]->id, 'time' => $helper->getDayTime(), 'status' => 'A'])->sum('amount');
        
                    TotalUserEarning::where(['user_id' => $getSecondaryLevelAgent[0]->id])
                                    ->update([
                                        'total_earnings' => $helper->getUserCurrentEarning($getSecondaryLevelAgent[0]->id) + $getSumOfComission,
                                        'total_without_deduct' => $helper->getTotalWithoutDeduct($getSecondaryLevelAgent[0]->id) + $getSumOfComission
                                    ]);
                    
                    ReferalComission::where(['id' => $getRefId->id])->update(['status' => 'I']);
                }
            }

            $getSelectedUser = User::where(['id' => $helper->getUserIdByUsername($shareDetails->transfer_to_id)])->first();
            $refCpyTmp = $getSelectedUser->refNoCpy;
            $tmpCount = 1;
            
            do {

                $getAgent = User::where(['refNo' => $refCpyTmp])->first();
    
                if ($getAgent != null) {
    
                    $getComissionLog = ReferalComissionLog::where(['user_id' => $getAgent->id])->first();
    
                    $check_earning_status_three = TotalUserEarning::where(['user_id' => $getAgent->id])->first();
    
                    if ($check_earning_status_three->earning_status == "A") {
                        if ($tmpCount == 1) {
                            ReferalPEOValueComission::create([
                                'from_user_id' => $helper->getUserIdByUsername($shareDetails->transfer_to_id),
                                'to_user_id' => $getAgent->id,
                                'peo_value' => ($helper->getPackageDetailsByPackageId($shareDetails->package_id))->peo_value,
                                'time' => $helper->getDayTime()
                            ]);
        
                            if ($getComissionLog != null) {
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                                    ->update([
                                                        'peo_value' => ($getComissionLog->peo_value + ($helper->getPackageDetailsByPackageId($shareDetails->package_id))->peo_value)
                                                    ]);

                            } else {
                                ReferalComissionLog::create([
                                    'user_id' => $getAgent->id,
                                    'peo_value' => (($helper->getPackageDetailsByPackageId($shareDetails->package_id))->peo_value),
                                ]);
                                // ReferalComissionLog::create([
                                //     'user_id' => $getAgent->id,
                                //     'peo_value' => 0,
                                // ]);
                            }
                        } else {
                            // ReferalPEOValueComission::create([
                            //     'from_user_id' => $packageUpdateRequest->user_id,
                            //     'to_user_id' => $getAgent->id,
                            //     'peo_value' => ($package_details->peo_value / 2),
                            //     'time' => $helper->getDayTime()
                            // ]);
    
                            ReferalPEOValueComission::create([
                                'from_user_id' => $helper->getUserIdByUsername($shareDetails->transfer_to_id),
                                'to_user_id' => $getAgent->id,
                                'peo_value' => ($helper->getPackageDetailsByPackageId($shareDetails->package_id))->peo_value,
                                'time' => $helper->getDayTime()
                            ]);
    
                            // ReferalPEOValueComission::create([
                            //     'from_user_id' => $packageUpdateRequest->user_id,
                            //     'to_user_id' => $getAgent->id,
                            //     'peo_value' => 0,
                            //     'time' => $helper->getDayTime()
                            // ]);
        
                            if ($getComissionLog != null) {
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                                    ->update([
                                                        'peo_value' => ($getComissionLog->peo_value + ($helper->getPackageDetailsByPackageId($shareDetails->package_id)->peo_value)),
                                                    ]);
                                // ReferalComissionLog::where(['user_id' => $getAgent->id])
                                //                         ->update([
                                //                             'peo_value' => 0
                                //                         ]);
                            } else {
                                // ReferalComissionLog::create([
                                //     'user_id' => $getAgent->id,
                                //     'peo_value' => ($package_details->peo_value / 2),
                                // ]);
    
                                ReferalComissionLog::create([
                                    'user_id' => $getAgent->id,
                                    'peo_value' => $helper->getPackageDetailsByPackageId($shareDetails->package_id)->peo_value,
                                ]);
                                
                            }
                        }
        
                        $myUserdataList = User::where(['id' =>  $getAgent->id])->first();
                        $myReferralsList = User::where(['refNoCpy' => $myUserdataList->refNo])->get();
                        $getLeftSidePEOValueAmt = 0;
                        $getRightSidePEOValueAmt = 0;
    
                        if (!empty($myReferralsList[0])) {
                            $getLeftSidePEOValueAmt = ReferalPEOValueComission::where(['from_user_id' => $myReferralsList[0]->id])->sum('peo_value');
                        }
    
                        if (!empty($myReferralsList[1])) {
                            $getRightSidePEOValueAmt = ReferalPEOValueComission::where(['from_user_id' => $myReferralsList[1]->id])->sum('peo_value');
                        }
    
                        $PEOtoUSD = 0;
                        $remainPEOValue = 0;
    
                        $get_agent_active_total = DB::table('packages')->select(DB::raw("SUM(packages.package_amount) as total"))
                                                                        ->join('package_payments', 'packages.id', '=', 'package_payments.package_id')
                                                                        ->where('package_payments.status', '=', 'A')
                                                                        ->where('package_payments.user_id', '=', $getAgent->id)
                                                                        ->get();
    
                        //dd($get_agent_active_total[0]->total);
    
                        $current_agent_total = TotalUserEarning::where(['user_id' => $getAgent->id])->first();
                        $current_peo_value_agent = ReferalComissionLog::where(['user_id' => $getAgent->id])->first();
    
                        if ($package_details->peo_value == 1) {
                            if ($get_agent_active_total[0]->total >= 100) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 2,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                                    ->update([
                                                        'total_earnings' => ($current_agent_total->total_earnings + 2),
                                                        'total_without_deduct' => ($current_agent_total->total_without_deduct + 2)
                                                    ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                                    ->update([
                                                        'peo_value' => (($current_peo_value_agent->peo_value - 1) < 0 ? 0 : $current_peo_value_agent->peo_value - 1)
                                                    ]);
                            }
                        }else if ($package_details->peo_value == 2) {
                            if ($get_agent_active_total[0]->total >= 150) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 3,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                        ->update([
                                            'total_earnings' => ($current_agent_total->total_earnings + 3),
                                            'total_without_deduct' => ($current_agent_total->total_without_deduct + 3)
                                        ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                        ->update([
                                            'peo_value' => (($current_peo_value_agent->peo_value - 2) < 0 ? 0 : ($current_peo_value_agent->peo_value - 2))
                                        ]);
                            }
                        } else if ($package_details->peo_value == 3) {
                            if ($get_agent_active_total[0]->total >= 200) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 4,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                        ->update([
                                            'total_earnings' => ($current_agent_total->total_earnings + 4),
                                            'total_without_deduct' => ($current_agent_total->total_without_deduct + 4)
                                        ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                        ->update([
                                            'peo_value' => (($current_peo_value_agent->peo_value - 3) < 0 ? 0 : ($current_peo_value_agent->peo_value - 3))
                                        ]);
                            }
                        } else if ($package_details->peo_value == 4) {
                            if ($get_agent_active_total[0]->total >= 250) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 5,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                        ->update([
                                            'total_earnings' => ($current_agent_total->total_earnings + 5),
                                            'total_without_deduct' => ($current_agent_total->total_without_deduct + 5)
                                        ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                        ->update([
                                            'peo_value' => (($current_peo_value_agent->peo_value - 4) < 0 ? 0 : ($current_peo_value_agent->peo_value - 4))
                                        ]);
                            }
                        } else if ($package_details->peo_value == 5) {
                            if ($get_agent_active_total[0]->total >= 500) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 6,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                        ->update([
                                            'total_earnings' => ($current_agent_total->total_earnings + 6),
                                            'total_without_deduct' => ($current_agent_total->total_without_deduct + 6)
                                        ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                        ->update([
                                            'peo_value' => (($current_peo_value_agent->peo_value - 5) < 0 ? 0 : ($current_peo_value_agent->peo_value - 5))
                                        ]);
                            }
                        } else if ($package_details->peo_value == 10) {
                            if ($get_agent_active_total[0]->total >= 1000) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 12,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                        ->update([
                                            'total_earnings' => ($current_agent_total->total_earnings + 12),
                                            'total_without_deduct' => ($current_agent_total->total_without_deduct + 12)
                                        ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                        ->update([
                                            'peo_value' => (($current_peo_value_agent->peo_value - 10) < 0 ? 0 : ($current_peo_value_agent->peo_value - 10))
                                        ]);
                            }
                        }else if ($package_details->peo_value == 20) {
                            if ($get_agent_active_total[0]->total >= 2000) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 24,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                        ->update([
                                            'total_earnings' => ($current_agent_total->total_earnings + 24),
                                            'total_without_deduct' => ($current_agent_total->total_without_deduct + 24)
                                        ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                        ->update([
                                            'peo_value' => (($current_peo_value_agent->peo_value - 20) < 0 ? 0 : ($current_peo_value_agent->peo_value - 20))
                                        ]);
                            }
                        }else if ($package_details->peo_value == 40) {
                            if ($get_agent_active_total[0]->total >= 4000) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 48,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                        ->update([
                                            'total_earnings' => ($current_agent_total->total_earnings + 48),
                                            'total_without_deduct' => ($current_agent_total->total_without_deduct + 48)
                                        ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                        ->update([
                                            'peo_value' => (($current_peo_value_agent->peo_value - 40) < 0 ? 0 : ($current_peo_value_agent->peo_value - 40))
                                        ]);
                            }
                        }else if ($package_details->peo_value == 80) {
                            if ($get_agent_active_total[0]->total >= 8000) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 96,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                        ->update([
                                            'total_earnings' => ($current_agent_total->total_earnings + 96),
                                            'total_without_deduct' => ($current_agent_total->total_without_deduct + 96)
                                        ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                        ->update([
                                            'peo_value' => (($current_peo_value_agent->peo_value - 80) < 0 ? 0 : ($current_peo_value_agent->peo_value - 80))
                                        ]);
                            }
                        }else if ($package_details->peo_value == 160) {
                            if ($get_agent_active_total[0]->total >= 8000) {
                                $saveUSDT = DailyPEOValueUSDTLog::create([
                                            'user_id' => $getAgent->id,
                                            'amount' => 192,
                                            'time' => $helper->getDayTime(),
                                            'status' => 'A'
                                        ]);
    
                                TotalUserEarning::where(['user_id' => $getAgent->id])   
                                        ->update([
                                            'total_earnings' => ($current_agent_total->total_earnings + 192),
                                            'total_without_deduct' => ($current_agent_total->total_without_deduct + 192)
                                        ]);
    
                                ReferalComissionLog::where(['user_id' => $getAgent->id])
                                        ->update([
                                            'peo_value' => (($current_peo_value_agent->peo_value - 160) < 0 ? 0 : ($current_peo_value_agent->peo_value - 160))
                                        ]);
                            }
                        }
                    }
    
                    $refCpyTmp = $getAgent->refNoCpy;
                    $tmpCount += 1;
                } else {
                    break;
                }
            }
            while(true);

            if ($deductPackageAmountFromEarning) {
                Session()->flash('status', 'Package Shared Successfully.');
            } else {
                Session()->flash('status', 'There is An Error Occur while share Package.');
            }
        } else {
            Session()->flash('status', 'There is An Error Occur while share Package');
        }

        return redirect()->back();
    }

    public function showmanagePackagesPageForAdmin() {

        $all_packages = Package::all();
        return view('admin.manage_packages')->with(['packages' => $all_packages]);
    }

    public function createNewPackageForUser(request $packageDetails) {

        $packageDetails->validate([
            'package_name' => 'required',
            'package_amount' => 'required | numeric'
        ]);

        $package = Package::create([
            'package_name' => $packageDetails->package_name,
            'package_amount' => $packageDetails->package_amount,
            'peo_value' => $packageDetails->peo_value,
            'rate' => $packageDetails->package_rate,
            'status' => $packageDetails->status
        ]);

        if ($package) {
            Session()->flash('status', 'Package Created Successfully.');
            return redirect()->back();
        } else {
            Session()->flash('status', 'There is an Error Occured.');
            return redirect()->back();
        }
    }

    public function getTXNNumberByUserId(Request $data) {

        $uid = $data->query('uid');

        if ($uid != null) {
            $getTXNDetails = PackagePayment::where(['id' => $uid])->get();
            return response()->json(['status'=>'1', 'data' => $getTXNDetails[0]->txn_number]);
        }
    }

    public function updatePackageByPackageId(Request $package_details) {

        $package_details->validate([
            'update_package_name' => 'required',
            'update_package_rate' => 'required | numeric',
            'update_package_amount' => 'required | numeric',
            'update_peo_value' => 'required | numeric',
            'update_status' => 'required'
        ]);

        $update_package = Package::where(['id' => $package_details->package_id])
                                    ->update([
                                        'package_name' => $package_details->update_package_name,
                                        'rate' => $package_details->update_package_rate,
                                        'package_amount' => $package_details->update_package_amount,
                                        'peo_value' => $package_details->update_peo_value,
                                        'status' => $package_details->update_status
                                    ]);
        
        if ($update_package) {
            Session()->flash('status', 'Package Updated Successfully.');
        } else {
            Session()->flash('status', 'There is an Error Occur.');
        }

        return redirect()->back();
    }

    public function showHoldPackagesPageForAdmin() {
        
        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];
            $hold_packages_list = DB::table('package_payments')->select('package_payments.id', 'users.username', 'packages.package_name'
                                                                        ,   'package_payments.earning_status', 'package_payments.created_at')
                                                                ->join('users', 'users.id', '=', 'package_payments.user_id')
                                                                ->join('packages', 'packages.id', '=', 'package_payments.package_id')
                                                                ->where('package_payments.earning_status', '=', 'H')
                                                                ->get();

            return view('admin.hold_earnings')->with(['hold_packages_list' => $hold_packages_list]);
        } else {
            Session()->flash('status', 'Access Denied.');
            return redirect('/signin');
        }
    }

    public function updateStartEarningStatusByUID(Request $request_details) {
        
        $update_success = PackagePayment::where(['id' => $request_details->uid])
                                            ->update([
                                                'earning_status' => 'A'
                                            ]);

        if ($update_success) {
            Session()->flash('status', 'Package Earning Started.');
        } else {
            Session()->flash('status', 'There is an Error Occur');
        }

        return redirect()->back();
    }

    public function deletePackageByPackageId(Request $package_id) {

        if (Session()->has('member')) {
            Package::where('id', $package_id)->delete();

            Session()->flash('status', 'Operation Complete.');
        } else {
            Session()->flash('status', 'Operation Not Complete.');
        }

        return redirect()->back();
    }

    public function getPackageAmountByPackageId(Request $request) {

        $package_id = $request->query('packageId');

        if ($package_id != null) {
            $getPackageAmount = Package::where(['id' => $package_id])->first();
            return response()->json(['status'=>'1', 'data' => $getPackageAmount->package_amount]);
        }
    }
}
