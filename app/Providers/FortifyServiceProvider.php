<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Models\SysConfig;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        $locale = config('app.locale');

        Fortify::loginView(function () use ($locale) {
            return view('auth.login', [
                'locale' => $locale,
                'registration_disabled' => SysConfig::config()->registration_disabled ?? null
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () use ($locale) {
            return view('auth.forgot-password', ['locale' => $locale]);
        });

        Fortify::resetPasswordView(function (Request $request) use ($locale) {
            return view('auth.reset-password', ['request' => $request, 'locale' => $locale]);
        });

        Fortify::verifyEmailView(function () use ($locale) {
            return view('auth.verify-email', ['locale' => $locale]);
        });

        Fortify::confirmPasswordView(function () use ($locale) {
            return view('auth.confirm-password', ['locale' => $locale]);
        });


        Fortify::registerView(function () use ($locale) {
            if (SysConfig::config()->registration_disabled ?? null) return redirect(route('login'));
            return view('auth.register', ['locale' => $locale]);
        });

        // register new LoginResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Auth\LoginResponse::class
        );
                
        // register new LogoutResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LogoutResponse::class,
            \App\Http\Auth\LogoutResponse::class
        );


        // register new TwofactorLoginResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\TwoFactorLoginResponse::class,
            \App\Http\Auth\LoginResponse::class
        );
    }
}
