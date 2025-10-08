<?php

namespace App\Exception;

use RuntimeException;

class BadRequestException extends  RuntimeException
{
    protected $message = 'The request could not be understood or was missing required parameters.';
    protected $code = 400;
}