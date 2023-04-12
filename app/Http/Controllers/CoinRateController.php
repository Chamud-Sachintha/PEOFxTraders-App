<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoinRate;

class CoinRateController extends Controller
{

    public function manageDailyCoinRates() {

        if (Session()->has('member')) {
            $all_rates = CoinRate::all();
            return view('admin.manage_daily_coin_rates')->with(['all_rates' => $all_rates]);
        } else {
            Session()->flash('status', 'Access Denied.');
            return redirect('/signin');
        }
    }

    public function addNewCoinRateForSystem(Request $coinRateDetails) {

        $coinRateDetails->validate([
            'coin_name' => 'required', 'coin_rate' => 'required | numeric',
        ]);

        $add_new_coin_rate = CoinRate::create([
            'coin_name' => $coinRateDetails->coin_name,
            'coin_rate' => $coinRateDetails->coin_rate,
            'status' => $coinRateDetails->status
        ]);

        if ($add_new_coin_rate) {
            Session()->flash('status', 'Coin Rate Added Successfully.');
        } else {
            Session()->flash('status', 'There is an Error Occur.');
        }

        return redirect()->back();
    }

    public function updateCoinRate(Request $coin_rate_details) {

        $update_success = CoinRate::where(['id' => $coin_rate_details->id])
                                        ->update([
                                            'coin_name' => $coin_rate_details->coin_name,
                                            'coin_rate' => $coin_rate_details->coin_rate,
                                            'status' => $coin_rate_details->status
                                        ]);

        if ($update_success) {
            Session()->flash('status', 'Coin Rate Update Successfully.');
        } else {
            Session()->flash('status', 'There is an Error Occur.');
        }

        return redirect()->back();
    }

    public function deleteCoinRateById($id) {

        $delete_success = CoinRate::where(['id' => $id])->delete();

        if ($delete_success) {
            Session()->flash('status', 'Coin Rate Deleted Successfully.');
        } else {
            Session()->flash('status', 'There is an Error Occur.');
        }

        return redirect()->back();
    }
}
