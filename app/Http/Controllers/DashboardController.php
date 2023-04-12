<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ReferalComission;
use App\Models\P2PTransaction;
use App\Models\TotalUserEarning;
use App\Models\ReferalPEOValueComission;
use App\Models\ReferalComissionLog;
use App\Models\CoinRate;
use App\Helpers\AppHelper;
use App\Models\User;
use Session;

class DashboardController extends Controller
{
    public function showDashboardToUsers() {

        $helper = new AppHelper();

        $currentTotalEarning = 0;
        
        if (Session()->has('member')) {
            $user_id = Session()->get('member')['id'];
            // $coin_array = $helper->getEachReferalLineIDsByUserId($user_id);
            // $helper->getLeftPathSubTrees($user_id);

            // dd($coin_array);

            $get_coin_rates = CoinRate::where(['status' => 'A'])->get();

            $totalInvestment = DB::table('package_payments')->select(DB::raw('sum(packages.package_amount) as total_inv'))
                                                            ->join('packages', 'packages.id', '=', 'package_payments.package_id')
                                                            ->where('package_payments.user_id', '=', $user_id)
                                                            ->where('package_payments.status', '=', 'A')
                                                            ->get();

            $totalReferralAmount = ReferalComission::where(['to_user_id' => $user_id])->sum('amount');

            $totalP2PEarnings = P2PTransaction::where(['transfer_to_id' => $user_id])->sum('amount');

            $getCurrentEarning = TotalUserEarning::where(['user_id' => $user_id])->select('total_earnings')->get();
            
            $get_total_amount_without_deduct = TotalUserEarning::where(['user_id' => $user_id])->select('total_without_deduct')->get();
            

            
            if (!empty($getCurrentEarning[0])) {
                $currentTotalEarning = $getCurrentEarning[0]->total_earnings;
            }

            if (!empty($get_total_amount_without_deduct[0])) {
                $totalAmountWithoutDeduct = $get_total_amount_without_deduct[0]->total_without_deduct;
            }

            $get_total_peo_values = ReferalComissionLog::where(['user_id' => $user_id])->sum('peo_value');

        
            return view('dashboard')->with(['total_inv' => $totalInvestment, 'total_ref' => $totalReferralAmount
                                        ,   'p2p_total' => $totalP2PEarnings, 'current_total_earning' => $currentTotalEarning
                                        ,   'coin_rates' => $get_coin_rates
                                        ,   'total_without_deduct' => $totalAmountWithoutDeduct
                                        ,   'total_peo_values' => $get_total_peo_values]);
        } else {
            return redirect('/signin')->with(['status' => 'Please Login Before Access Admin Page']);
        }
    }
}
