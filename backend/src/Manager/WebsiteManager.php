<?php

namespace App\Manager;

use App\Entity\Website;
use App\Exception\NoContentException;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;

class WebsiteManager
{
    public function __construct
    (
        Private WebsiteRepository $websiteRepository,
        Private EntityManagerInterface $entityManager,
        private WebsiteStatusLogManager $websiteStatusLogManager
    ) {}

    public function getAllWebsites(): array
    {
        $websites = $this->websiteRepository->findAll();
        if(!$websites) return ['No websites found'];
        $data=[];
        foreach ($websites as $website) {
            $name = $website->getName();
            $url = $website->getUrl();
            $id = $website->getId();
            $lastStatusLog = $this->websiteStatusLogManager->getLastStatusLog($id) ?? "No status log";
            $data[] = [
                'id' => $id,
                'name' => $name,
                'url' => $url,
                'lastStatusLog' => $lastStatusLog['statusCode']
            ];
        }
        return $data;
    }

    public function getWebsite(
        int $id
    ): array
    {
        $website = $this->websiteRepository->find($id);
        if(!$website) throw new NoContentException;
        $data=[];
            $name = $website->getName();
            $url = $website->getUrl();
            $id = $website->getId();
            $statusLogs = $this->websiteStatusLogManager->getStatusLogs($id) ?? "";
        $data[] = [
                'name' => $name,
                'url' => $url,
                'id' => $id,
                'statusLogs' => $statusLogs
            ];
        return $data;
    }

    public function addWebsite(
        string $name,
        string $url
    ): void
    {
        $website = new Website();
        $website->setName($name);
        $website->setUrl($url);
        $this->entityManager->persist($website);
        $this->entityManager->flush();
    }
    public function editWebsite(
        int $id,
        string $name,
        string $url
    ): void
    {
        $website = $this->websiteRepository->find($id);
        if(!$website) throw new NoContentException;
        $website->setName($name);
        $website->setUrl($url);
        $this->entityManager->persist($website);
        $this->entityManager->flush();
    }

    public function deleteWebsite(
        int $id,
    ): void
    {
        $website = $this->websiteRepository->find($id);
        if(!$website) throw new NoContentException;
        $this->entityManager->remove($website);
        $this->entityManager->flush();
    }
}