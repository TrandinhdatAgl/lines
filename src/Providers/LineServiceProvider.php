<?php

namespace Lines\Lines\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Lines\Lines\Facades\LineFacade as FacadesLineFacade;
use Lines\Lines\Supports\LineFacade;

/**
 * LineServiceProvider
 */
class LineServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('line_facade', function () {
            return new LineFacade();
        });

        $alias = AliasLoader::getInstance();

        $alias->alias('LineFacade', FacadesLineFacade::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
