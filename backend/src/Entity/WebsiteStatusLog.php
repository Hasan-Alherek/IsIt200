<?php

namespace App\Entity;

use App\Repository\WebsiteStatusLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebsiteStatusLogRepository::class)]
class WebsiteStatusLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'websiteStatusLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Website $websiteId = null;

    #[ORM\Column]
    private ?int $statusCode = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $checkedAt = null;

    #[ORM\Column]
    private ?float $responseTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebsiteId(): ?Website
    {
        return $this->websiteId;
    }

    public function setWebsiteId(?Website $websiteId): static
    {
        $this->websiteId = $websiteId;

        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getCheckedAt(): ?\DateTimeImmutable
    {
        return $this->checkedAt;
    }

    public function setCheckedAt(\DateTimeImmutable $checkedAt): static
    {
        $this->checkedAt = $checkedAt;

        return $this;
    }

    public function getResponseTime(): ?float
    {
        return $this->responseTime;
    }

    public function setResponseTime(float $responseTime): static
    {
        $this->responseTime = $responseTime;

        return $this;
    }
}
