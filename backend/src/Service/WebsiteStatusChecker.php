<?php

namespace App\Service;

use App\Entity\WebsiteStatusLog;
use App\Manager\WebsiteManager;
use App\Manager\WebsiteStatusLogManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebsiteStatusChecker
{
    public function __construct
    (
        private HttpClientInterface $httpClient,
        private WebsiteStatusLogManager $websiteStatusLogManager,
        private WebsiteManager $websiteManager,
    ) {}

    public function check(): bool
    {
        $websites = $this->websiteManager->getAllWebsites();
        foreach ($websites as &$website) {
            $website = $this->request($website);
        }
         return $this->websiteStatusLogManager->add(
            $websites,
        );
    }
    private function request($website): array
    {
        $responseTimeStart = microtime(true);
        $response = $this->httpClient->request(
            'GET',
            $website['url'],
            [
                'timeout' => 5
            ]
        );
        $responseTimeEnd = microtime(true);
        $responseTime = ($responseTimeEnd - $responseTimeStart) * 1000;
        $statusCode = $response->getStatusCode() ?? 0;
        $checkedAt = new \DateTimeImmutable();
        $website += [
            "statusCode" => $statusCode,
            "responseTime" => $responseTime,
            "checkedAt" => $checkedAt
        ];
        return $website;
    }

}
