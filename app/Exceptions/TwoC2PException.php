<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class TwoC2PException extends Exception
{
    public function __construct(
        string $message,
        public readonly ?string $respCode = null,
        public readonly array $payload = [],
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
