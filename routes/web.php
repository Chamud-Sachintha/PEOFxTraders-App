<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKYCCheckController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\EarningController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CoinRateController;
use App\Http\Controllers\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/contact-us', [IndexController::class, 'showContactPageToUser']);

Route::get('/about-us', [IndexController::class, 'showAboutUsPageForUser']);

Route::get('/projects', [IndexController::class, 'showProjectPageForUser']);

Route::get('/', [IndexController::class, 'showWelcomePage']);

Route::get('/signin', [UsersController::class, 'showSigninPage']);

Route::post('/signin/validate', [UsersController::class, 'validateLogin']);

Route::get('/signup', [UsersController::class, 'showSignUpPage']);

Route::post('/user/create', [UsersController::class, 'createNewUser']);

Route::get('/user/verify/{token}', [UsersController::class, 'verifyEmail']);

Route::get('/verification-notice', [UsersController::class, 'showResendNoticePage'])->name('verification-notice');

Route::post('/resend_verification_link', [UsersController::class, 'resendVerificationLink']);

Route::get('/app', [DashboardController::class, 'showDashboardToUsers']);

Route::get('/app/profile', [ProfileController::class, 'showProfilePage']);

Route::post('/updateProfile', [ProfileController::class, 'updateProfileInformations']);

Route::post('/updatePassword', [ProfileController::class, 'changePassword']);

Route::get('/app/profile/kyc-info', [ProfileController::class, 'showKYCDocumentsUploadPage']);

Route::post('/uploadKYCDocuments', [ProfileController::class, 'submitKYCDocumentsToAdmin']);

Route::get('/app/my-earnings', [EarningController::class, 'showMyEarningsPage']);

Route::get('/app/active-packages', [PackageController::class, 'showActivePackagesPage']);

Route::get('/activate-package/{id}', [PackageController::class, 'showActivatePackageAndDepositPage']);

Route::post('/activePackageAndDeposit', [PackageController::class, 'activePackageAndDeposit']);

Route::get('/app/my-wallet', [EarningController::class, 'showMyWalletPageToUser']);

Route::post('/executeP2PTransfer', [EarningController::class, 'createNewP2PTransferBetweenUsereAccounts']);

Route::get('/app/my-withdraw', [WithdrawController::class, 'showWithdrawPageToUser']);

Route::post('/madeWithdrawRequest', [WithdrawController::class, 'createWithdrawRequestForAdmin']);

Route::get('/app/share-package', [PackageController::class, 'showSharePackagePageForUser']);

Route::post('/createSharePackageForUser',[PackageController::class, 'makeSharePackageForSpecificUser']);

Route::get('/app/my-geneology', [ProfileController::class, 'showReferralChainByChart']);

Route::get('/getTXNNumberByUID', [PackageController::class, 'getTXNNumberByUserId'])->name('getTXNNumberByUID');

Route::get('/getUsernamesList', [UsersController::class, 'getUsernamesListToAutocomplete'])->name('getUsernamesList');

Route::get('/getUserActivatedPackages', [UsersController::class, 'getUserActivatedPackageList'])->name('getUserActivatedPackages');

Route::post('/changeProfileAvatar', [ProfileController::class, 'changeProfileAvatar']);

Route::get('/getDocRejectedReasonByUserId', [ProfileController::class, 'getDocRejectedReasonByUserId'])->name('getDocRejectedReasonByUserId');

Route::get('/getPackageAmountByPackageId', [PackageController::class, 'getPackageAmountByPackageId'])->name('getPackageAmountByPackageId');

Route::get('/signout', [UsersController::class, 'signoutUser']);


/*
    admin routes starts here
*/

Route::get('/admin/signin', [AdminController::class, 'showAdminSigninPage']);

Route::post('/admin/validate', [AdminController::class, 'authenticateAdmin']);

Route::get('/admin/app', [AdminController::class, 'showAdminDashboard']);

Route::get('/admin/kyc-info', [AdminKYCCheckController::class, 'showSubmitedKYCDocumentsPage']);

Route::get('/getKYCImagesByUserId', [AdminKYCCheckController::class, 'getKYCImagesByUserId'])->name('getKYCImagesByUserId');

Route::post('/updateKYCDocStatus', [AdminKYCCheckController::class, 'submitCheckingStatusToKYCDocuments']);

Route::get('/admin/show-package-requests', [PackageController::class, 'showPackageRequestsForAdmin']);

Route::post('/updatePackageRequestByAdmin', [PackageController::class, 'approveOrRejectPackageRequestByAdmin']);

Route::get('/admin/withdraw-requests', [WithdrawController::class, 'showWithdrawRequestsPageForAdmin']);

Route::get('/admin/all-users', [UsersController::class, 'showAllUsersDetailsPageForAdmin']);

Route::get('/admin/packages', [PackageController::class, 'showmanagePackagesPageForAdmin']);

Route::post('/createNewPackage', [PackageController::class, 'createNewPackageForUser']);

Route::post('/updatePackageByPackageId', [PackageController::class, 'updatePackageByPackageId']);

Route::get('/deletePackageByPackageId/{id}', [PackageController::class, 'deletePackageByPackageId']);

Route::post('/updateWithdrawRequest', [WithdrawController::class, 'updateWithdrawRequestByAdmin']);

Route::get('/viewSelectedUser/{user_id}', [UsersController::class, 'viewSelectedUserByUserId']);

Route::get('/admin/coin-rates', [CoinRateController::class, 'manageDailyCoinRates']);

Route::get('/getCurrentTotalEarning', [WithdrawController::class, 'getCurrentTotalEarning'])->name('getCurrentTotalEarning');

Route::get('/getWalletInfoByUserId', [WithdrawController::class, 'getWalletInfoByUserId'])->name('getWalletInfoByUserId');

Route::get('/admin/show-hold-packages', [PackageController::class, 'showHoldPackagesPageForAdmin']);

Route::post('/startPackageEarning', [PackageController::class, 'updateStartEarningStatusByUID']);

Route::post('/createNewCoinRate', [CoinRateController::class, 'addNewCoinRateForSystem']);

Route::post('/updateSelectedPackageById', [CoinRateController::class, 'updateCoinRate']);

Route::get('/deleteCoinRateById/{id}', [CoinRateController::class, 'deleteCoinRateById']);

Route::get('/admin/sign-out', [AdminController::class, 'adminSignOut']);

// password forgot routes

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');