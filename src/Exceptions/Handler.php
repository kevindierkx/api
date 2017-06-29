<?php namespace Kevindierkx\Api\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Handler implements ExceptionHandler
{
    /**
     * The parent Illuminate exception handler instance.
     *
     * @var ExceptionHandler
     */
    protected $parentHandler;

    /**
     * Exception response factory.
     *
     * @var ResponseFactory
     */
    protected $factory;

    /**
     * Create a new instance of the middleware.
     *
     * @param  ExceptionHandler  $exceptions
     * @param  ResponseFactory   $factory
     */
    public function __construct(ExceptionHandler $parentHandler, ResponseFactory $factory)
    {
        $this->parentHandler = $parentHandler;
        $this->factory = $factory;
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $exception)
    {
        $this->parentHandler->report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        return $this->factory->make($exception);
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Exception  $e
     * @return void
     */
    public function renderForConsole($output, Exception $exception)
    {
        return $this->parentHandler->renderForConsole($output, $exception);
    }
}
