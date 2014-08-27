<?php namespace NpmWeb\GitApiClient\Laravel;

use Illuminate\Support\Manager;
use NpmWeb\GitApiClient\BitbucketApiClient;
use Bitbucket\API\Repositories;
use Bitbucket\API\Http\Listener\OAuthListener;

class GitApiClientManager extends Manager {

    static $packageName = 'git-api-client';

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    protected function createDriver($driver)
    {
        $gitApiClient = parent::createDriver($driver);

        // any other setup needed

        return $gitApiClient;
    }

    /**
     * Create an instance of the jQuery driver.
     *
     * @return \NpmWeb\ClientValidationGenerator\Laravel\JqueryValidationGenerator
     */
    public function createBitbucketDriver()
    {
        $repositories = new Repositories;
        $repositories->getClient()->addListener(
            new OAuthListener(array(
                'oauth_consumer_key'
                    => $this->app['config']->get(self::$packageName.'::oauth_consumer_key'),
                'oauth_consumer_secret'
                    => $this->app['config']->get(self::$packageName.'::oauth_consumer_secret')
            ))
        );
        return new BitbucketApiClient($repositories);
    }

    /**
     * Get the default authentication driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']->get(self::$packageName.'::driver');
    }

    /**
     * Set the default authentication driver name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']->set(self::$packageName.'::driver', $name);
    }

}
