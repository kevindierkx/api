<?php namespace Kevindierkx\Api\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Kevindierkx\Api\Exceptions\Handler as ExceptionHandler;
use Kevindierkx\Api\Exceptions\ResponseFactory as ExceptionResponseFactory;
use Kevindierkx\Api\Middleware\CatchHttpExceptions;
use Kevindierkx\Api\Routing\Response;
use ReflectionClass;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerExceptionResponseFactory();
        $this->registerExceptionHandler();
        $this->registerMiddleware();
        $this->registerResponseMacros();
    }

    /**
     * Register the exception response factory.
     *
     * @return void
     */
    protected function registerExceptionResponseFactory()
    {
        $this->app->singleton('api.exceptions.factory', function ($app) {
            return new ExceptionResponseFactory(
                $app,
                $app['config']->get('api.exceptions.formats'),
                $app['config']->get('api.exceptions.default'),
                $app['config']->get('app.debug')
            );
        });
    }

    /**
     * Register the exception handler.
     *
     * @return void
     */
    protected function registerExceptionHandler()
    {
        $this->app->singleton('api.exceptions.handler', function ($app) {
            return new ExceptionHandler(
                $app[ExceptionHandlerContract::class],
                $app['api.exceptions.factory']
            );
        });
    }

    /**
     * Register the middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $this->app->singleton(CatchHttpExceptions::class, function ($app) {
            return new CatchHttpExceptions($app['api.exceptions.handler']);
        });
    }

    /**
     * Register the reponse macros.
     *
     * @return void
     */
    protected function registerResponseMacros()
    {
        $reflection = new ReflectionClass(Response::class);

        foreach ($reflection->getMethods() as $method) {
            $name = $method->getName();

            if (strpos($name, 'error') === 0) {
                $this->registerMacro($name);
            }
        }
    }

    /**
     * Register a reponse macro.
     *
     * @param  string  $name
     * @return void
     */
    protected function registerMacro($name)
    {
        ResponseFactory::macro($name, function () use ($name) {
            return call_user_func_array(Response::class.'::'.$name, func_get_args());
        });
    }
}
