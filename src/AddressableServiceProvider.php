<?php
namespace Nh\Addressable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class AddressableServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

      // TRANSLATIONS
      $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'addressable');

      // MIGRATIONS
      $this->loadMigrationsFrom(__DIR__.'/../stubs/database/migrations/2020_08_24_000000_create_addresses_table.php');

      // VENDORS
        $this->publishes([
            __DIR__.'/../database/migrations/2020_08_24_000000_create_addresses_table.php' => base_path('database/migrations/2020_08_24_000000_create_addresses_table.php'),
            __DIR__.'/Models/Address.php' => app_path('Models/Address.php'),
        ], 'addressable');

    }
}
