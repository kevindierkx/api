<?php namespace Kevindierkx\Api\Exceptions;

use Exception;
use Illuminate\Container\Container;
use Kevindierkx\Api\Exceptions\FormatInterface;
use RuntimeException;

class ResponseFactory
{
    /**
     * Illuminate container instance.
     *
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $formats;

    /**
     * @var string
     */
    protected $defaultFormat;

    /**
     * Indicates if we are in debug mode.
     *
     * @var bool
     */
    protected $debug;

    /**
     * Create a new response factory instance.
     *
     * @param  Container  $container
     * @param  array      $formats
     * @param  string     $defaultFormat
     * @param  boolean    $debug
     */
    public function __construct(Container $container, array $formats = [], $defaultFormat = null, $debug = false)
    {
        $this->container = $container;
        $this->formats = $formats;
        $this->defaultFormat = $defaultFormat;
        $this->debug = $debug;
    }

    /**
     * Make a new response using the message format.
     *
     * @param  Exception  $exception
     * @param  string     $format
     * @return Response
     */
    public function make(Exception $exception, $format = null)
    {
        $format = ! is_null($format) ? $this->getFormat($format) : $this->defaultFormat();

        $instance = $this->container->make($format);

        return $instance->render($exception, $this->inDebug());
    }

    /**
     * Get default format.
     *
     * @return mixed
     */
    public function defaultFormat()
    {
        return $this->defaultFormat;
    }

    /**
     * Get registered format.
     *
     * @param  string  $name
     * @return mixed
     */
    public function getFormat($name)
    {
        return isset($this->formats[$name]) ? $this->formats[$name] : null;
    }

    /**
     * Register format.
     *
     * @param  string  $name
     * @param  mixed   $format
     * @return self
     */
    public function setFormat($name, $format)
    {
        $this->formats[$name] = $format;

        return $this;
    }

    /**
     * Get the debug state.
     *
     * @return bool
     */
    public function inDebug()
    {
        return $this->debug;
    }

    /**
     * Set the debug mode.
     *
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }
}
