<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use App\Http\Controllers\PhoneVerificationNotificationController;
use App\Http\Controllers\PhoneVerificationPromptController;
use App\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use App\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ShortUrlController;
use Inertia\Inertia;

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

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/', [EventController::class, 'index'])
        ->name('home');

    Route::get('/event/create', [EventController::class, 'create'])
        ->name('event.create');

    Route::post('/event/store', [EventController::class, 'store'])
        ->name('event.store');

    Route::get('/contacts/search', [ContactController::class, 'search'])
        ->name('contacts.search');
    
});

Route::group(['middleware' => ['auth', 'verified', 'event.ended']], function () {
    Route::get('/event/view/{event}', [EventController::class, 'show'])
        ->middleware('invite.expired')
        ->name('event.view');

    Route::get('/event/details/{event}', [EventController::class, 'details'])
        ->name('event.details');

    Route::post('/event/details/{event}/store', [EventController::class, 'storeDetails'])
        ->name('event.details.store');

    Route::get('/event/edit/{event}', [EventController::class, 'edit'])
        ->name('event.edit');

    Route::put('/event/edit/{event}/update', [EventController::class, 'update'])
        ->name('event.update');

    Route::get('/event/edit/{event}/calendar', [EventController::class, 'calendar'])
        ->name('event.calendar');

    Route::delete('/event/edit/{event}/delete', [EventController::class, 'delete'])
        ->name('event.delete');

    Route::post('/event/edit/{event}/invite', [InviteController::class, 'send'])
        ->name('event.invite.send');

    Route::put('/event/edit/{event}/invite/{invite}/update', [InviteController::class, 'update'])
        ->name('event.invite.update');

    Route::delete('/event/edit/{event}/invite/{invite}/delete', [InviteController::class, 'delete'])
        ->name('event.invite.delete');
});

Route::get('/invite/{invite}', [InviteController::class, 'show'])
        ->name('invite.view');

Route::put('/invite/{invite}/respond', [InviteController::class, 'respond'])
        ->name('invite.respond');

Route::get('/invite/{invite}/calendar', [InviteController::class, 'calendar'])
        ->name('invite.calendar');

Route::get('/s/{short_url:short}', [ShortUrlController::class, 'route'])
        ->name('short.route');

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    // Authentication...
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware(['guest'])
        ->name('login');

    $limiter = config('fortify.limiters.login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest',
            $limiter ? 'throttle:'.$limiter : null,
        ]));

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Password Reset...
    if (Features::enabled(Features::resetPasswords())) {
        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
            ->middleware(['guest'])
            ->name('password.request');

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware(['guest'])
            ->name('password.phone');

        Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
            ->middleware(['guest'])
            ->name('password.reset');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware(['guest'])
            ->name('password.update');
    }

    // Registration...
    if (Features::enabled(Features::registration())) {
        Route::get('/register', [RegisteredUserController::class, 'create'])
            ->middleware(['guest'])
            ->name('register');

        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware(['guest']);
    }

    // Phone Verification...
    Route::get('/phone/verify', [PhoneVerificationPromptController::class, 'index'])
        ->middleware(['auth'])
        ->name('verification.notice');

    Route::post('/phone/verify', [PhoneVerificationPromptController::class, 'verify'])
    	->middleware(['auth'])
    	->name('verification.verify');

    Route::post('/phone/verification-notification', [PhoneVerificationNotificationController::class, 'store'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.send');

    // Profile Information...
    if (Features::enabled(Features::updateProfileInformation())) {
        Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
            ->middleware(['auth']) 
            ->name('user-profile-information.update');
    }

    // Passwords...
    if (Features::enabled(Features::updatePasswords())) {
        Route::put('/user/password', [PasswordController::class, 'update'])
            ->middleware(['auth'])
            ->name('user-password.update');
    }

    // Password Confirmation...
    Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->middleware(['auth'])
        ->name('password.confirm');

    Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware(['auth']);

    Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
        ->middleware(['auth'])
        ->name('password.confirmation');

});