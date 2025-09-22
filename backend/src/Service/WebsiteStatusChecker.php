<?php

namespace App\Service;

use App\Entity\WebsiteStatusLog;
use App\Manager\WebsiteStatusLogManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebsiteStatusChecker
{
    public function __construct
    (
        private HttpClientInterface $httpClient,
        private WebsiteStatusLogManager $websiteStatusLogManager,
    ) {}

    public function check($website):  WebsiteStatusLog
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
        $websiteId= $website['id'];

         return $this->websiteStatusLogManager->add(
            $websiteId,
            $statusCode,
            $responseTime,
            $checkedAt
        );
    }

}
