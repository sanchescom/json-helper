<?php

namespace Sanchescom\Support\Exceptions;

class UnableDecodeJsonException extends JsonException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Unable to decode JSON: {$message}", $code, $previous);
    }
}
