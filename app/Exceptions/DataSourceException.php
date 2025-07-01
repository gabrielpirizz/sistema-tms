<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DataSourceException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
    public function report(): bool
    {
        return true;
    }

    public function render()
    {
        return response()->view('errors.data-source', ['message' => $this->getMessage()], 500);
    }
} 