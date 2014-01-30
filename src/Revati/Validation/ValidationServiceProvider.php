<?php namespace Revati\Validation;

use Illuminate\Validation\ValidationServiceProvider as ServiceProvider;
use Request;
use Config;

class ValidationServiceProvider extends ServiceProvider {

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
        $this->package('revati/validation');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPresenceVerifier();

        $this->app->bindShared('validator', function($app)
        {
            $validator = new Factory($app['translator'], $app);

            // The validation presence verifier is responsible for determining the existence
            // of values in a given data collection, typically a relational database or
            // other persistent data stores. And it is used to check for uniqueness.
            if (isset($app['validation.presence']))
            {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            return $validator;
        });

        $this->exceptionHandler();
    }

    private function exceptionHandler()
    {
        // Deal with ValidationException
        $this->app->error(function(ValidationException $e)
        {
            if( Request::ajax() )
            {
                $response = Config::get('validation::ajaxResponse');
                return $response($e);
            }

            $response = Config::get('validation::response');
            return $response($e);
        });
    }
}
