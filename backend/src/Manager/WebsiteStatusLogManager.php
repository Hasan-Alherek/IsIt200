<?php

namespace App\Manager;

use App\Entity\WebsiteStatusLog;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;

class WebsiteStatusLogManager
{

    public function __construct
    (
        Private WebsiteRepository $websiteRepository,
        Private EntityManagerInterface $entityManager
    ) {}

    public function add(
        $websiteId,
        $statusCode,
        $responseTime,
        $checkedAt
    ) : WebsiteStatusLog
    {
        $websiteStatusLog = new WebsiteStatusLog();
        $website = $this->websiteRepository->find($websiteId);
        if (!$website) {
            throw new \Exception('Website not found');
        }
        $websiteStatusLog->setWebsiteId($website); // pass the entity, not the ID
        $websiteStatusLog->setStatusCode($statusCode);
        $websiteStatusLog->setResponseTime($responseTime);
        $websiteStatusLog->setCheckedAt($checkedAt);
        $this->entityManager->persist($websiteStatusLog);
        $this->entityManager->flush();
        return $websiteStatusLog;
    }
    public function deleteAllLogs(): int
    {
        $websiteStatusLogRepository = $this->entityManager->getRepository(WebsiteStatusLog::class);
        return $websiteStatusLogRepository->deleteAll();
    }
}