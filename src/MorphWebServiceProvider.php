<?php

namespace WalkerChiu\MorphWeb;

use Illuminate\Support\ServiceProvider;

class MorphWebServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
           __DIR__ .'/config/morph-web.php' => config_path('wk-morph-web.php'),
        ], 'config');

        // Publish migration files
        $from = __DIR__ .'/database/migrations/';
        $to   = database_path('migrations') .'/';
        $this->publishes([
            $from .'create_wk_morph_web_table.php'
                => $to .date('Y_m_d_His', time()) .'_create_wk_morph_web_table.php'
        ], 'migrations');

        $this->loadTranslationsFrom(__DIR__.'/translations', 'php-morph-web');
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/php-morph-web'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                config('wk-morph-web.command.cleaner')
            ]);
        }

        config('wk-core.class.morph-web.web')::observe(config('wk-core.class.morph-web.webObserver'));
        config('wk-core.class.morph-web.webLang')::observe(config('wk-core.class.morph-web.webLangObserver'));
    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
    }

    /**
     * Merges user's and package's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        if (!config()->has('wk-morph-web')) {
            $this->mergeConfigFrom(
                __DIR__ .'/config/morph-web.php', 'wk-morph-web'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ .'/config/morph-web.php', 'morph-web'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $config = $this->app->make('config');
            $content = $config->get($key, []);

            $config->set($key, array_merge(
                require $path, $content
            ));
        }
    }
}
