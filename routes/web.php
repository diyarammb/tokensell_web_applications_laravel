<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'PublicController@welcome')->name('welcome');
Route::get('/language', 'PublicController@language')->name('language');
Route::get('/investments', 'PublicController@investments')->name('investments');
Route::get('/page/{slug}', 'PageController')->name('show.page');
Route::post('/policy-consent', 'PublicController@gdprCookie')->name('gdpr.cookie');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', 'AuthController@loginForm')->name('auth.login.form');
    Route::post('/login', 'AuthController@login')->name('auth.login');
    Route::post('/auth/social/confirm', 'AuthController@confirmSocialAuth')->name('auth.social.confirm');
    Route::get('/auth/social/confirm', 'AuthController@confirmSocialView')->name('auth.social.confirm.signup');
    Route::get('/auth/social/verify', 'AuthController@authSocialVerify')->name('auth.social.verify');
    Route::get('/auth/social/{type}', 'AuthController@socialAuth')->name('auth.social');
    Route::get('/auth/2fa', 'AuthController@authVerifyForm')->name('auth.2fa.form');
    Route::post('/auth/2fa', 'AuthController@authVerify2FA')->name('auth.2fa');

    Route::get('/register', 'AuthController@registerForm')->name('auth.register.form');
    Route::post('/register', 'AuthController@register')->name('auth.register');

    Route::get('/password/forget', 'AuthController@forgetPasswordView')->name('auth.forget.form');
    Route::post('/password/forget', 'AuthController@forgetPassword')->name('auth.forget');
});
Route::get('/auth/social/{type}/callback', 'AuthController@socialCallback')->name('auth.social.callback');
Route::get('/password/reset', 'AuthController@resetPasswordView')->name('auth.reset.page');
Route::post('/password/reset', 'AuthController@resetPassword')->name('auth.reset');


Route::get('/email/confirm', 'AuthController@confirm')->name('auth.confirm');
Route::get('/verify', 'AuthController@verifyEmail')->name('auth.email.verify');
Route::get('/verify/email', 'AuthController@verifyEmailUpdate')->name('auth.email.update.verify');

Route::get('/account/verify', 'AuthController@accountVerification')->name('auth.email.verification');
Route::post('/email/resend', 'AuthController@resendVerifyEmail')->name('auth.email.resend');
Route::post('/email/change', 'AuthController@updateEmailAndVerify')->name('auth.email.change');
Route::post('/submit-form', 'ContactController@contact')->name('contact.form');

Route::post('logout', 'AuthController@logout')->middleware('auth')->name('auth.logout');
Route::get('invite', 'AuthController@referral')->name('auth.invite');

// Route while maintaince enable
Route::get('maintenance', 'MaintenanceController@index')->name('maintenance');
Route::get('/admin/login', 'AuthController@loginForm')->name('admin.login');

Route::get('test', function () {
});
