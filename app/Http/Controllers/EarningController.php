<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Package;
use App\Models\TotalUserEarning;
use App\Models\User;
use App\Models\P2PTransaction;
use App\Models\PackagePayment;
use App\Models\Earning;
use App\Models\ReferalComission;
use App\Models\ReferalComissionLog;
use App\Models\ReferalPEOValueComission;
use App\Models\DailyPEOValueUSDTLog;
use App\Helpers\AppHelper;

class EarningController extends Controller
{
    public function showMyEarningsPage() {

        $helper = new AppHelper();

        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id']; 

            if ($helper->isUserKYCApprovedOrNot($user_id)) {
                $getTotalApprovedEarnings = DB::table('package_payments')->select(DB::raw('sum(packages.package_amount) as total_deposits'))
                                                                        ->join('packages', 'packages.id', '=', 'package_payments.package_id')
                                                                        ->where('package_payments.user_id', '=', $user_id)
                                                                        ->get();
                $getApprovedPackageList = PackagePayment::where(['user_id' => $user_id, 'status' => 'A'])->get();
                $totalEarningsByUser = TotalUserEarning::where(['user_id' => $user_id])->sum('total_earnings');
                $earningTableDetails = DB::table('earnings')->select('earnings.user_id', 'earnings.daily_earning', 'earnings.p2p_earning_daily'
                                                                    ,   'earnings.ref_earning_daily', 'earnings.daily_total_earning'
                                                                    ,   'earnings.total_earning', 'earnings.created_at')
                                                            ->where('earnings.user_id', $user_id)
                                                            ->get();
                $totalP2PEarnings = P2PTransaction::where(['transfer_to_id' => $user_id])->sum('amount');
                $totalDirectRefComissionByDay = ReferalComission::where(['to_user_id' => $user_id, 'time' => $helper->getDayTime()])->sum('amount');
                $totalPEOValueComissionByDay = ReferalPEOValueComission::where(['to_user_id' => $user_id, 'time' => $helper->getDayTime()])->sum('peo_value');
                $currentPeoValue = ReferalComissionLog::where(['user_id' => $user_id])->sum('peo_value');
                $finalPEOValueUSDT = DailyPEOValueUSDTLog::where(['user_id' => $user_id, 'time' => $helper->getDayTime()])->sum('amount');
                
                return view('my_earnings')->with(['totalApprovedDeposits' => $getTotalApprovedEarnings
                                                ,   'approvedPackageList' => $getApprovedPackageList
                                                ,   'totalUserEarnings' => $totalEarningsByUser
                                                ,   'dailyEarningList' => $earningTableDetails
                                                ,   'totalDirectRefComission' => $totalDirectRefComissionByDay
                                                ,   'totalPEOValueComission' => $currentPeoValue
                                                ,   'total_p2p_earnings' => $totalP2PEarnings
                                                ,   'finalPEOValueUSDT' => $totalPEOValueComissionByDay]);
            } else {
                Session()->flash('status', 'Please Submit KYC Documents.');
                return redirect('/app/profile/kyc-info');
            }
        } else {
            Session()->flash('status', 'Please Login Before Access Page.');
            return redirect('signin');
        }
    }

    public function showMyWalletPageToUser() {

        $totalEarningsByUser = null;

        $helper = new AppHelper();
        $username_list = User::all();

        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];

            $totalEarningsByUser = TotalUserEarning::where(['user_id' => $user_id])->sum('total_earnings');
            $transactionHistory = DB::table('p2_p_transactions')->select('p2_p_transactions.id', 'users.username', 'p2_p_transactions.amount', 'p2_p_transactions.created_at')
                                                                    ->join('users', 'users.id', '=', 'p2_p_transactions.transfer_from_id')
                                                                    ->where('p2_p_transactions.transfer_to_id', '=', $user_id)
                                                                    ->get();
            
            if ($helper->isUserKYCApprovedOrNot($user_id)) {
                return view('my_wallet')->with(['totalUserEarnings' => $totalEarningsByUser
                    ,   'users_list' => $username_list
                    ,   'transaction_list' => $transactionHistory
                ]);
            } else {
                Session()->flash('status', 'Please Submit KYC Documents.');
                return redirect('/app/profile/kyc-info');
            }
        } else {
            Session()->flash('status', 'Access Denied.');
            return redirect('/signin');
        }
    }

    public function createNewP2PTransferBetweenUsereAccounts(Request $transferDetails) {

        $helper = new AppHelper();

        $transferDetails->validate([
            'transfer_to_id' => 'required',
            'amount' => 'required | numeric'
        ]);
        
        $get_user_id = User::where(['username' => $transferDetails->transfer_to_id])->select('id')->get();

        if ($transferDetails->transfer_from_id == $get_user_id[0]->id) {
            Session()->flash('status', 'Invalid Username.');
            return redirect()->back();
        }

        $getUserTotalEarning = TotalUserEarning::where(['user_id' => $transferDetails->transfer_from_id])->sum('total_earnings');
        
        if ($transferDetails->amount <= $getUserTotalEarning && $transferDetails->amount > 0) {
            if ($transferDetails->amount <= 10) {
                Session()->flash('status', 'Amount should Be Greater Than 10 USDT');
                return redirect()->back();
            }
            $newP2PTransfer = P2PTransaction::create([
                'transfer_from_id' => $transferDetails->transfer_from_id,
                'transfer_to_id' => $get_user_id[0]->id,
                'amount' => $transferDetails->amount,
                'time' => $helper->getDayTime()
            ]);
            //dd($transferDetails);
            if (empty($newP2PTransfer) != true) {
                $getFromUserCurrentEarning = TotalUserEarning::where(['user_id' => $transferDetails->transfer_from_id])->select('total_earnings')->get();
                $getToUserCurrentEaring = TotalUserEarning::where(['user_id' => $get_user_id[0]->id])->select('total_earnings')->get();
                //dd($getToUserCurrentEaring);
                TotalUserEarning::where(['user_id' => $transferDetails->transfer_from_id])
                                    ->update([
                                        'total_earnings' => $getFromUserCurrentEarning[0]->total_earnings - $transferDetails->amount
                                    ]);

                TotalUserEarning::where(['user_id' => $get_user_id[0]->id])
                                    ->update([
                                        'total_earnings' => $getToUserCurrentEaring[0]->total_earnings + $transferDetails->amount
                                    ]);

                Session()->flash('status', 'P2P Transaction Successfully.');
                return redirect()->back();
            } else {
                Session()->flash('status', 'Transaction Not Successfully.');
                return redirect()->back();
            }
        } else {
            Session()->flash('status', 'Amount Should Be Greater Than Your Earnings');
            return redirect()->back();
        }
    }
}
