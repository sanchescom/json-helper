<?php

namespace Sanchescom\Support\Exceptions;

class UnableEncodeJsonException extends JsonException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Unable to encode JSON: {$message}", $code, $previous);
    }
}
