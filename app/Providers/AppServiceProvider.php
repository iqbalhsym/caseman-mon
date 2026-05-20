<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Pagination\Paginator;
use LdapRecord\Laravel\Events\Import\Saved;
use App\Listeners\AssignUserRole;

class AppServiceProvider extends ServiceProvider
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
        // AdminLTE Menu Listener removed (migrated to Tabler layout)
        Paginator::useBootstrapFive();

        // Register LDAP role assignment listener
        \Illuminate\Support\Facades\Event::listen(Saved::class, AssignUserRole::class);
    }
}