<?php namespace Kevindierkx\Api\Exceptions\Formats;

use Exception;

interface FormatInterface
{
    /**
     * Renders the exception to a generic response.
     *
     * @param  Exception  $exception
     * @param  bool       $debug
     * @return Response
     */
    public function render(Exception $exception, $debug = false);
}
