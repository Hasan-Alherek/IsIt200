<?php

namespace App\Manager;

use App\Entity\Website;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;

class WebsiteManager
{
    public function __construct
    (
        Private WebsiteRepository $websiteRepository,
        Private EntityManagerInterface $entityManager
    ) {}

    public function getAllWebsites(): array
    {
        $websites = $this->websiteRepository->findAll();
        $data=[];
        foreach ($websites as $website) {
            $name = $website->getName();
            $url = $website->getUrl();
            $id = $website->getId();
            $data[] = [
                'name' => $name,
                'url' => $url,
                'id' => $id
            ];
        }
        return $data;
    }

    public function getWebsite($id): array
    {
        $website = $this->websiteRepository->find($id);
        $data=[];
            $name = $website->getName();
            $url = $website->getUrl();
            $id = $website->getId();
            $data[] = [
                'name' => $name,
                'url' => $url,
                'id' => $id,
            ];
        return $data;
    }

    public function addWebsite(
        string $name,
        string $url
    ): Website
    {
        $website = new Website();
        $website->setName($name);
        $website->setUrl($url);
        $this->entityManager->persist($website);
        $this->entityManager->flush();
        return $website;
    }
    public function editWebsite(
        int $id,
        string $name,
        string $url
    ): Website
    {
        $website = $this->websiteRepository->find($id);
        $website->setName($name);
        $website->setUrl($url);
        $this->entityManager->persist($website);
        $this->entityManager->flush();
        return $website;
    }
}