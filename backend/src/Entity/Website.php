<?php

namespace App\Entity;

use App\Repository\WebsiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebsiteRepository::class)]
class Website
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 500)]
    private ?string $url = null;

    /**
     * @var Collection<int, WebsiteStatusLog>
     */
    #[ORM\OneToMany(targetEntity: WebsiteStatusLog::class, mappedBy: 'websiteId', orphanRemoval: true)]
    private Collection $websiteStatusLogs;

    public function __construct()
    {
        $this->websiteStatusLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, WebsiteStatusLog>
     */
    public function getWebsiteStatusLogs(): Collection
    {
        return $this->websiteStatusLogs;
    }

    public function addWebsiteStatusLog(WebsiteStatusLog $websiteStatusLog): static
    {
        if (!$this->websiteStatusLogs->contains($websiteStatusLog)) {
            $this->websiteStatusLogs->add($websiteStatusLog);
            $websiteStatusLog->setWebsiteId($this);
        }

        return $this;
    }

    public function removeWebsiteStatusLog(WebsiteStatusLog $websiteStatusLog): static
    {
        if ($this->websiteStatusLogs->removeElement($websiteStatusLog)) {
            // set the owning side to null (unless already changed)
            if ($websiteStatusLog->getWebsiteId() === $this) {
                $websiteStatusLog->setWebsiteId(null);
            }
        }

        return $this;
    }
}
