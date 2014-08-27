<?php namespace NpmWeb\GitApiClient\Laravel;

use Illuminate\Support\ServiceProvider;
use NpmWeb\GitApiClient\GitApiClientInterface;

class GitApiClientServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // @see https://coderwall.com/p/svocrg
        $this->package('npmweb/git-api-client', null, __DIR__.'/../../../');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared( GitApiClientInterface::class, function($app)
        {
            return (new GitApiClientManager($app))->driver();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
