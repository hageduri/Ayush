<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\AllUsersPolicy;
use App\Policies\UserPolicy;
use Filament\Events\ServingFilament;
use Filament\Facades\Filament;
use Filament\FilamentManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

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
    public function boot(FilamentManager $filament): void
    {

        Gate::policy(User::class, UserPolicy::class);



    }
}
