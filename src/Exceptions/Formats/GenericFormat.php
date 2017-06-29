<?php namespace Kevindierkx\Api\Exceptions\Formats;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class GenericFormat implements FormatInterface
{
    /**
     * The error response format.
     *
     * @var array
     */
    protected $format = [
        'message' => ':message',
        'status_code' => ':status_code',
        'errors' => ':errors',
        'code' => ':code',
        'debug' => ':debug',
    ];

    /**
     * Renders the exception to a generic response.
     *
     * @param  Exception  $exception
     * @param  bool       $debug
     * @return Response
     */
    public function render(Exception $exception, $debug = false)
    {
        $response = [];

        foreach ($this->format as $name => $value) {
            $method = 'format'.ucfirst(Str::camel($name));

            $response[$name] = $this->{$method}($exception, $debug) ?: $value;
        }

        $response = $this->recursivelyRemoveEmptyReplacements($response);

        return new Response($response, $this->getStatusCode($exception), $this->getHeaders($exception));
    }

    /**
     * Format message.
     *
     * @param  Exception  $exception
     * @param  bool       $debug
     * @return string
     */
    protected function formatMessage(Exception $exception, $debug)
    {
        if (! $message = $exception->getMessage()) {
            $message = sprintf('%d %s', $statusCode, Response::$statusTexts[$statusCode]);
        }

        return $message;
    }

    /**
     * Format status code.
     *
     * @param  Exception  $exception
     * @param  bool       $debug
     * @return int
     */
    protected function formatStatusCode(Exception $exception, $debug)
    {
        return $this->getStatusCode($exception);
    }

    /**
     * Format errors.
     *
     * @param  Exception  $exception
     * @param  bool       $debug
     * @return mixed
     */
    protected function formatErrors(Exception $exception, $debug)
    {
        // if ($exception instanceof MessageBagErrors && $exception->hasErrors()) {
        //     return $exception->getErrors();
        // }
    }

    /**
     * Format code.
     *
     * @param  Exception  $exception
     * @param  bool       $debug
     * @return mixed
     */
    protected function formatCode(Exception $exception, $debug)
    {
        if ($code = $exception->getCode()) {
            return $code;
        }
    }

    /**
     * Format debug.
     *
     * @param  Exception  $exception
     * @param  bool       $debug
     * @return mixed
     */
    protected function formatDebug(Exception $exception, $debug)
    {
        if ($debug) {
            return [
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'class' => get_class($exception),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        }
    }

    /**
     * Get the status code from the exception.
     *
     * @param  Exception  $exception
     * @return int
     */
    protected function getStatusCode(Exception $exception)
    {
        return $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
    }

    /**
     * Get the headers from the exception.
     *
     * @param  Exception  $exception
     * @return array
     */
    protected function getHeaders(Exception $exception)
    {
        return $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];
    }

    /**
     * Recursirvely remove any empty replacement values in the response array.
     *
     * @param  array  $input
     * @return array
     */
    protected function recursivelyRemoveEmptyReplacements(array $input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->recursivelyRemoveEmptyReplacements($value);
            }
        }

        return array_filter($input, function ($value) {
            if (is_string($value)) {
                return ! starts_with($value, ':');
            }
            return true;
        });
    }
}
