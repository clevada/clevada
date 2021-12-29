<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Schema;

use Cache;

use App\Models\Core;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        $this->app->bind('path.public', function () {
            return getcwd();
        });
       
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Schema::defaultStringLength(191);

        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }


        // mail settings:
        $config = Core::config();        

        config()->set('mail', array_merge(config('mail'), [
            'driver' => $config->mail_driver ?? 'smtp',
            'host' => $config->smtp_host ?? 'smtp.mailgun.org',
            'port' => $config->smtp_port ?? '587',
            'encryption' => $config->smtp_encryption ?? 'tls',
            'username' => $config->smtp_username ?? null,
            'password' => $config->smtp_password ??null,
            'from' => [
                'address' => $config->mail_from_address ?? null,
                'name' => $config->mail_from_name ?? null
            ]
        ]));
        
        config()->set('services', array_merge(config('services'), [
            'mailgun' => [
                'domain' => $config->mailgun_domain ?? null,
                'secret' => $config->mailgun_secret ?? null,
                'endpoint' => $config->mailgun_endpoint ?? 'api.mailgun.net'
            ],
            'ses' => [
                'key' => $config->aws_key ?? null,
                'secret' => $config->aws_secret ?? null,
                'region' => $config->aws_region ?? 'us-east-1'
            ]
        ]));

    }
}
