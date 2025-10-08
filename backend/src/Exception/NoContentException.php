<?php

namespace App\Exception;


use RuntimeException;

class NoContentException extends  RuntimeException
{
    protected $message = 'The requested resource was not found.';
    protected $code = 404;
}