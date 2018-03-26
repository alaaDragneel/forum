<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Thread' => 'App\Policies\ThreadPolicy',
        'App\Reply'  => 'App\Policies\ReplyPolice',
        'App\User'   => 'App\Policies\UserPolice',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot ()
    {
        $this->registerPolicies();

        Gate::before(function ($user)
        {
            if ( $user->type == 'admin' ) return true;
        });
    }
}
