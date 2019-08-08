<?php
namespace tricciardi\ptzipcodes;

use Illuminate\Support\ServiceProvider;

class PtZipCodesProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

      $this->publishes([
                          __DIR__.'/config/ptzipcodes.php' => config_path('ptzipcodes.php'),
                        ]);
      $this->loadMigrationsFrom(__DIR__.'/migrations');

      //set commands
      if ($this->app->runningInConsole()) {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\ZipCodes::class,
            ]);
        }
      }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->mergeConfigFrom( __DIR__.'/config/ptzipcodes.php', 'ptzipcodes' );
    }
}
