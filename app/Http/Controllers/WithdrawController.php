<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TotalUserEarning;
use App\Models\Package;
use App\Models\User;
use App\Models\Withdraw;
use App\Helpers\AppHelper;

class WithdrawController extends Controller
{
    public function showWithdrawPageToUser() {

        $helper = new AppHelper();

        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];

            $totalEarningsByUser = TotalUserEarning::where(['user_id' => $user_id])->sum('total_earnings');
            $withdrawRequestsList = Withdraw::where(['user_id' => $user_id])->get();

            if ($helper->isUserKYCApprovedOrNot($user_id)) {
                return view('withdraw')->with([
                    'totalUserEarnings' => $totalEarningsByUser,
                    'withdraw_requests_list' => $withdrawRequestsList
                ]);
            } else {
                Session()->flash('status', 'Please Submit Your KYC Documents.');
                return redirect('app/profile/kyc-info');
            }
        } else {
            Session()->flash('status', 'Access Denied.');
            return redirect('/signin');
        }
    }

    public function createWithdrawRequestForAdmin(Request $withdrawDetails) {

        $withdrawDetails->validate([
            'amount' => 'required | numeric'
        ]);

        $getUserTotalEarning = TotalUserEarning::where(['user_id' => $withdrawDetails->user_id])->sum('total_earnings');

        if ($withdrawDetails->amount <= $getUserTotalEarning && $withdrawDetails->amount > 0) {
            $madeWithdrawRequest = Withdraw::create([
                'user_id' => $withdrawDetails->user_id,
                'amount' => $withdrawDetails->amount,
                'status' => 'P'
            ]);

            if ($madeWithdrawRequest != null) {

                $currentTotalEarning = TotalUserEarning::where(['user_id' => $withdrawDetails->user_id])->select('total_earnings')->get();
                TotalUserEarning::where(['user_id' => $withdrawDetails->user_id])
                                    ->update([
                                        'total_earnings' => ($currentTotalEarning[0]->total_earnings) - $withdrawDetails->amount
                                    ]);
                Session()->flash('status', 'Withdrawal Request Send Successfully.');
                return redirect()->back();
            } else {
                Session()->flash('status', 'Withdrawal Request Send Error.');
                return redirect()->back();
            }
        } else {
            Session()->flash('status', 'Amount Should Be Greater Than Your Earnings');
            return redirect()->back();
        }
 
        Session()->flash('status', 'Withdraw Request Made Successfully.');
        return redirect()->back();
    }

    public function showWithdrawRequestsPageForAdmin() {

        $withdrawRequestsList = DB::table('withdraws')->select('withdraws.id','withdraws.user_id as uid', 'withdraws.amount', 'withdraws.status'
                                                            ,   'users.username', 'users.fname', 'users.lname')
                                                            ->join('users', 'users.id', '=', 'withdraws.user_id')
                                                            ->get();
        return view('admin.withdraw_requests')->with(['withdraw_requests_list' => $withdrawRequestsList]);
    }

    public function updateWithdrawRequestByAdmin(Request $withdrawDetails) {

        if ($withdrawDetails->status == "A") {
            $acceptWithdrawRequest = Withdraw::where(['user_id' => $withdrawDetails->user_id])
                                                ->update([
                                                    'status' => $withdrawDetails->status
                                                ]);
            if ($acceptWithdrawRequest != null) {
                Session()->flash('status', 'Withdraw Request Approve Success.');
            } else {
                Session()->flash('status', 'There is an Error Occur.');
            }

            return redirect()->back();
        } else {
            $currentTotal = TotalUserEarning::where(['user_id' => $withdrawDetails->user_id])->select('total_earnings')->get();
            $reverseWithdraw = TotalUserEarning::where(['user_id' => $withdrawDetails->user_id])
                                ->update([
                                    'total_earnings' => $currentTotal[0]->total_earnings + $withdrawDetails->amount
                                ]);
                                
            if ($reverseWithdraw != null) {
                Withdraw::where(['user_id' => $withdrawDetails->user_id, 'id' => $withdrawDetails->trans_number])
                            ->update([
                                'status' => 'R'
                            ]);
                Session()->flash('status', 'Withdraw Rejected Success.');
            } else {
                Session()->flash('status', 'There is an Error Reject Withdraw Contact System Admin');
            }

            return redirect()->back();
        }
    }

    public function getCurrentTotalEarning(Request $request) {
        $query = $request->get('userId');
        $total_current_earning = TotalUserEarning::where(['user_id' => $query])->first();
        $get_wallet_info = User::where(['id' => $query])->first();
        
        return response()->json(json_decode($total_current_earning));
    }

    public function getWalletInfoByUserId(Request $request) {
        $query = $request->get('userId');
        $get_wallet_info = User::where(['id' => $query])->first();
        
        return response()->json(json_decode($get_wallet_info));
    }
}
