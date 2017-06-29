<?php namespace Kevindierkx\Api\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CatchHttpExceptions
{
    /**
     * @var
     */
    protected $exceptions;

    /**
     * Create a new instance of the middleware.
     *
     * @param [type] $exceptions
     */
    public function __construct($exceptions)
    {
        $this->exceptions = $exceptions;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! empty($response->exception) && $response->exception instanceof HttpException) {
            $this->exceptions->report($response->exception);

            return $this->exceptions->render($request, $response->exception);
        }

        return $response;
    }
}
