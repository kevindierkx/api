<?php namespace Kevindierkx\Api\Routing;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ResponseService
{
    /**
     * Throw a HTTP error response.
     *
     * @param  string  $message
     * @param  int     $statusCode
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    public static function error($message, $statusCode)
    {
        throw new HttpException($statusCode, $message);
    }

    /**
     * Throw a HTTP '404 - Not Found' response.
     *
     * @param  string  $message
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    public static function errorNotFound($message = 'Not Found')
    {
        return static::error($message, 404);
    }

    /**
     * Throw a HTTP '400 - Bad Request' response.
     *
     * @param  string  $message
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    public static function errorBadRequest($message = 'Bad Request')
    {
        return static::error($message, 400);
    }

    /**
     * Throw a HTTP '403 - Forbidden' response.
     *
     * @param  string  $message
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    public static function errorForbidden($message = 'Forbidden')
    {
        return static::error($message, 403);
    }

    /**
     * Throw a HTTP '500 - Internal Server Error' response.
     *
     * @param  string  $message
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    public static function errorInternal($message = 'Internal Server Error')
    {
        return static::error($message, 500);
    }

    /**
     * Throw a HTTP '401 - Unauthorized' response.
     *
     * @param  string  $message
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    public static function errorUnauthorized($message = 'Unauthorized')
    {
        return static::error($message, 401);
    }

    /**
     * Throw a HTTP '405 - Method Not Allowed' response.
     *
     * @param  string  $message
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return void
     */
    public static function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        return static::error($message, 405);
    }
}
