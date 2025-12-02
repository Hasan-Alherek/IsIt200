<?php

namespace App\Service;

use App\Entity\WebsiteStatusLog;
use App\Manager\WebsiteManager;
use App\Manager\WebsiteStatusLogManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
            $website["requestTimeStart"] = microtime(true);
            $website["response"] = $this->startRequest($website["url"]);
        }

        foreach ($websites as &$website) {
            $requestTimeStart = $website["requestTimeStart"];
            $response = $website["response"];
            try {
                $statusCode = $response->getStatusCode();
            } catch (\Throwable $e) {
                $statusCode = 0;
            }
            $requestTimeEnd = microtime(true);
            $checkedAt = new \DateTimeImmutable();
            $responseTime = ($requestTimeEnd - $requestTimeStart) * 1000;
            $website += [
                "statusCode" => $statusCode,
                "responseTime" => $responseTime,
                "checkedAt" => $checkedAt
            ];
            unset($website['response'], $website['responseTimeStart']);
        }

         return $this->websiteStatusLogManager->add(
            $websites,
        );
    }
    private function startRequest($url): ResponseInterface
    {
        return $this->httpClient->request(
            'GET',
            $url,
            [
                'timeout' => 5
            ]
        );
    }
}
