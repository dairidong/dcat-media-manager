<?php

namespace Jatdung\MediaManager\Exceptions;

class DriverException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        if ($message) {
            $message = 'MediaManager driver exception: ' . $message;
        } else {
            $message = 'MediaManager driver exception.';
        }
        parent::__construct($message, $code, $previous);
    }
}
