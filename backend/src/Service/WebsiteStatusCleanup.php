<?php

namespace App\Service;

use App\Manager\WebsiteStatusLogManager;

class WebsiteStatusCleanup
{
    private string $deleteTime = "-1 day";
    public function __construct
    (
        private WebsiteStatusLogManager $websiteStatusLogManager,
    )
    {}

    public function cleanup(): int
    {
        $deleteDate = new \DateTimeImmutable($this->deleteTime);
        return $this->websiteStatusLogManager->deleteAllLogs($deleteDate);
    }
}