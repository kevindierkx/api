<?php namespace Kevindierkx\Api\Providers;

use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Kevindierkx\Api\Routing\ResponseService;
use ReflectionClass;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerResponseMacros();
    }

    /**
     * Register the reponse macros.
     *
     * @return void
     */
    protected function registerResponseMacros()
    {
        $reflection = new ReflectionClass(ResponseService::class);

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
            return call_user_func_array(ResponseService::class.'::'.$name, func_get_args());
        });
    }
}
