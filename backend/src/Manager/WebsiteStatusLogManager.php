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
//        $websiteStatusLog->setWebsiteId($websiteId);
        $websiteStatusLog->setStatusCode($statusCode);
        $websiteStatusLog->setResponseTime($responseTime);
        $websiteStatusLog->setCheckedAt($checkedAt);
        $website = $this->websiteRepository->find($websiteId);
        $website->addWebsiteStatusLog($websiteStatusLog);
        $this->entityManager->persist($websiteStatusLog);
        $this->entityManager->flush();
        return $websiteStatusLog;
    }
}