<?php

namespace App\Manager;

use App\Entity\WebsiteStatusLog;
use App\Repository\WebsiteRepository;
use App\Repository\WebsiteStatusLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class WebsiteStatusLogManager
{

    public function __construct
    (
        private WebsiteRepository      $websiteRepository,
        private WebsiteStatusLogRepository $websiteStatusLogRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface        $logger
    )
    {
    }

    public function add(
        $websites
    )
    {
        foreach ($websites as $websiteData) {
            $websiteStatusLog = new WebsiteStatusLog();
            $website = $this->websiteRepository->find($websiteData['id']);
            if (!$website) {
                $this->logger->warning('Website not found', ['id' => $websiteData['id']]);
                continue;
            }
            $websiteStatusLog->setWebsiteId($website);
            $websiteStatusLog->setStatusCode($websiteData['statusCode']);
            $websiteStatusLog->setResponseTime($websiteData['responseTime']);
            $websiteStatusLog->setCheckedAt($websiteData['checkedAt']);
            $this->entityManager->persist($websiteStatusLog);
        }
        try {
            $this->entityManager->flush();
            return true;
        } catch (Exception $e) {
            $this->logger->error('Failed to save WebsiteStatusLog', [
                'exception' => $e,
            ]);
            return false;
        }
    }

    public function cleanupBefore($deleteDate = "-1 day"): int
    {
        $websiteStatusLogRepository = $this->entityManager->getRepository(WebsiteStatusLog::class);
        return $websiteStatusLogRepository->deleteAllOlderThan($deleteDate);
    }
    public function getStatusLogs($websiteId)
    {
        $websiteStatusLogRepository = $this->entityManager->getRepository(WebsiteStatusLog::class);
        return $websiteStatusLogRepository->getStatusLogs($websiteId);
    }

    public function getLastStatusLog($websiteId): ?array
    {
        $lastStatus = $this->websiteStatusLogRepository->findOneBy(
            ['websiteId' => $websiteId],
            ['checkedAt' => 'DESC']
        );
        return $lastStatus ? [
            'statusCode' => $lastStatus->getStatusCode(),
        ] : null;
    }
}