<?php

namespace App\Providers;

use App\Services\Account\AccountService;
use App\Services\Account\IAccountService;
use App\Services\Credential\CredentialService;
use App\Services\Credential\ICredentialService;
use App\Services\User\IUserService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        //URL::forceScheme('https');
        

        DB::listen(function ($query) {
            Log::debug(
                'ELOQUENT QUERY TRIGGERED',
                [
                    'query' => $query->sql,
                    'bindings' => $query->bindings,
                    'exec_time' => $query->time]
            );
        });

        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IAccountService::class, AccountService::class);
        $this->app->bind(ICredentialService::class, CredentialService::class);
    }
}
