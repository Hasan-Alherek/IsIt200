<?php

namespace App\Service;

use App\Manager\WebsiteStatusLogManager;

class WebsiteStatusCleanup
{
    public function __construct
    (
        private WebsiteStatusLogManager $websiteStatusLogManager,
    )
    {}

    public function cleanup(): int
    {
        return $this->websiteStatusLogManager->deleteAllLogs();
    }
}