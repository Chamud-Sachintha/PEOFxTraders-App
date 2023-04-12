<?php

namespace App\Helpers;

use App\Models\KYCDocument;
use App\Models\TotalUserEarning;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;

class AppHelper {

    public function isUserKYCApprovedOrNot($userId) {

        $getKYCDocDetails = KYCDocument::where(['user_id' => $userId])->first();
 
        if ($getKYCDocDetails != null) {
            return ($getKYCDocDetails->status == "A" ? true : false);
        } else {
            return false;
        }
    }

    public function getUserCurrentEarning($user_id) {

        $getCurrentEarning = TotalUserEarning::where(['user_id' => $user_id])->first();
        return $getCurrentEarning->total_earnings;
    }

    public function getDayTime() {

        $dateString = null;

        $carbon = Carbon::now()->format('Y-m-d');
        $getExplodedDate = explode("-", $carbon);

        for ($i = 0; $i < count($getExplodedDate); $i++) {
            $dateString .= $getExplodedDate[$i];
        }

        return $dateString;
    }

    public function getUserIdByUsername($userName) {

        $user_id = null;
        $user = User::where(['username' => $userName])->first();

        if ($user) {
            $user_id = $user->id;
        }

        return $user_id;
    }

    public function getWalletInformationsByUserId($userId) {

        $wallet_informations = User::where(['id' => $userId])->first();
        return $wallet_informations;
    }

    public function getPackageAmountByPackageId($package_id) {

        $package = Package::where(['id' => $package_id])->first();
        return $package->package_amount;
    }

    public function getPackageDetailsByPackageId($packageId) {

        $package = Package::where(['id' => $packageId])->first();
        return $package;
    }

    public function getEachReferalLineIDsByUserId($userId) {

        $left = [];
        $right = [];

        $leftCount = 0;
        $rightCount = 0;
        $shift = 0; // 0 - L | 1 - R
        $stop = 0;
        $goLeft = 0;
        $goRight = 0;

        $isNoneLeft = 0;
        $isNoneRight = 0;

        $user_info = User::where(['id' => $userId])->first();
        $refTmp = $user_info->refNo;
        do {

            $myRefNo = User::where(['refNoCpy' => $refTmp])->get();

            if (empty($myRefNo[0])) {
                $isNoneLeft = 1;
            }

            if (empty($myRefNo[1])) {
                $isNoneRight = 1;
            }
            //dd($myRefNo);
            if ($stop == 0) {
                if (!empty($myRefNo[0])) {
                    $left[$leftCount] = $myRefNo[0]->id;
                    $leftCount += 1;
                }
    
                if (!empty($myRefNo[1])) {
                    $right[$rightCount] = $myRefNo[1]->id;
                    $rightCount += 1;
                }

            } else {
                if ($shift == 1) {
                    $left[$leftCount] = (!empty($myRefNo[0]) ? $myRefNo[0]->id : "N");
                    $leftCount += 1;
                    $left[$leftCount] = (!empty($myRefNo[1]) ? $myRefNo[1]->id : "N");
                    $leftCount += 1;
                } else {
                    $right[$rightCount] = (!empty($myRefNo[0]) ? $myRefNo[0]->id : "N");
                    $rightCount += 1;
                    $right[$rightCount] = (!empty($myRefNo[1]) ? $myRefNo[1]->id : "N");
                    $rightCount += 1;
                }
            }

            if ($shift == 0) {
                try {
                    $userTmp = User::where(['id' => $left[$goLeft]])->first();
                } catch (\Exception $e) {
                    break;
                }
                
                $shift = 1;
                $goLeft += 1;
            } else {
                try {
                    $userTmp = User::where(['id' => $right[$goRight]])->first();
                } catch (\Exception $e) {
                    break;
                }

                $shift = 0;
                $goRight += 1;
            }

            if ($isNoneLeft == 1 && $isNoneRight == 2) {
                break;
            }

            $refTmp = $userTmp->refNo;

        } while (true);

        dd([$left,$right]);
    }

    public function getLeftPathSubTrees($user_id) {

        $user_info = User::where(['id'=> $user_id])->first();

        $res = null;
        if ($user_info != null) {
            $myRefNo = $user_info->refNo;
            $res = $this->getLeftPathSubTrees($myRefNo);
        }

        dd($res);
    } 
}

?>