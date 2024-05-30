<?php

namespace App\Entity\Utils;

use Doctrine\ORM\Mapping as ORM;

trait DateTimeTrait
{
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    #[ORM\PrePersist]
    public function autoSetCreatedAt(): static
    {
        if (!$this->created_at) {
            $this->created_at = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    #[ORM\PreUpdate]
    public function autoSetUpdatedAt(): static
    {
        if (!$this->updated_at) {
            $this->updated_at = new \DateTimeImmutable();
        }

        return $this;
    }
}
