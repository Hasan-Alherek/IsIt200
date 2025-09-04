<?php

namespace App\Service;

use App\Entity\Website;

class WebsiteValidator
{
    private int $websiteLengthMax = 40;
    public function validateUrl(string $url)
    {
        if(empty($url)) return false;
        return (bool) filter_var($url, FILTER_VALIDATE_URL);
    }
    public function validateName(string $name)
    {
        if(empty($name)) return false;
        return is_string($name) && (strlen($name) < $this->websiteLengthMax);
    }
}