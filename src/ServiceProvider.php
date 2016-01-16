<?php
namespace Minhbang\AccessControl;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class AccessControlServiceProvider
 *
 * @package Minhbang\AccessControl
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'access-control');
        $this->loadViewsFrom(__DIR__ . '/../views', 'access-control');
        $this->publishes(
            [
                __DIR__ . '/../views'                     => base_path('resources/views/vendor/access-control'),
                __DIR__ . '/../lang'                      => base_path('resources/lang/vendor/access-control'),
                __DIR__ . '/../config/access-control.php' => config_path('access-control.php'),

                __DIR__ . '/../database/migrations/' .
                '2014_11_04_000000_create_role_groups_table.php'     => database_path('migrations/2014_11_04_000000_create_role_groups_table.php'),
                __DIR__ . '/../database/migrations/' .
                '2014_11_05_000000_create_roles_table.php'           => database_path('migrations/2014_11_05_000000_create_roles_table.php'),
                __DIR__ . '/../database/migrations/' .
                '2014_11_05_100000_create_role_user_table.php'       => database_path('migrations/2014_11_05_100000_create_role_user_table.php'),
                __DIR__ . '/../database/migrations/' .
                '2014_11_05_200000_create_permissions_table.php'     => database_path('migrations/2014_11_05_200000_create_permissions_table.php'),
                __DIR__ . '/../database/migrations/' .
                '2014_11_05_300000_create_permission_role_table.php' => database_path('migrations/2014_11_05_300000_create_permission_role_table.php'),
            ]
        );

        if (config('access-control.add_route') && !$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }
        // pattern filters
        $router->pattern('role', '[0-9]+');
        $router->pattern('role_group', '[0-9]+');
        $router->pattern('permission', '[0-9]+');
        // model bindings
        $router->model('role', 'Minhbang\AccessControl\Models\Role');
        $router->model('role_group', 'Minhbang\AccessControl\Models\RoleGroup');
        $router->model('permission', 'Minhbang\AccessControl\Models\Permission');

        $this->registerBladeExtensions();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/access-control.php', 'access-control');
        $this->app['access-control'] = $this->app->share(
            function () {
                return new AccessControl(
                    config('access-control.resources', [])
                );
            }
        );
        // add AccessControl alias
        $this->app->booting(
            function () {
                AliasLoader::getInstance()->alias('AccessControl', Facade::class);
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['access-control'];
    }

    /**
     * Register Blade extensions.
     *
     * @return void
     */
    protected function registerBladeExtensions()
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();
        $blade->directive('role', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->is{$expression}): ?>";
        });
        $blade->directive('endrole', function () {
            return "<?php endif; ?>";
        });
        $blade->directive('permission', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->can{$expression}): ?>";
        });
        $blade->directive('endpermission', function () {
            return "<?php endif; ?>";
        });
        $blade->directive('allowed', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->allowed{$expression}): ?>";
        });
        $blade->directive('endallowed', function () {
            return "<?php endif; ?>";
        });
    }
}