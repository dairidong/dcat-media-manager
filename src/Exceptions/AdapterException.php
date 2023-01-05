<?php

namespace Jatdung\MediaManager\Exceptions;

class AdapterException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        if ($message) {
            $message = 'MediaManager adapter exception: ' . $message;
        } else {
            $message = 'MediaManager adapter exception.';
        }
        parent::__construct($message, $code, $previous);
    }
}
