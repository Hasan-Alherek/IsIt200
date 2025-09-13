<?php

namespace App\Manager;

use App\Entity\Website;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;

class WebsiteManager
{
    public function __construct(WebsiteRepository $websiteRepository, EntityManagerInterface $entityManager )
    {
        $this->websiteRepository = $websiteRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllWebsites(): Array
    {
        $websites = $this->websiteRepository->findAll();
        $data=[];
        foreach ($websites as $website) {
            $name = $website->getName();
            $url = $website->getUrl();
            $data[] = [
                'name' => $name,
                'url' => $url,
            ];
        }
        return $data;
    }

    public function getWebsite($id): Array
    {
        $website = $this->websiteRepository->find($id);
        $data=[];
            $name = $website->getName();
            $url = $website->getUrl();
            $data[] = [
                'name' => $name,
                'url' => $url,
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