<?php

namespace App\Service;

use App\Exception\BadRequestException;

class WebsiteValidator
{
    private int $websiteLengthMax = 40;
    public function validateUrl(string $url): void
    {
        if(empty($url) || (!filter_var($url, FILTER_VALIDATE_URL))) throw new BadRequestException;
    }
    public function validateName(string $name): void
    {
        if(empty($name) || !is_string($name) || (strlen($name) > $this->websiteLengthMax)) throw new BadRequestException;
    }
}