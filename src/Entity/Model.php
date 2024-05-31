<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utils\EnableTrait;
use App\Entity\Utils\DateTimeTrait;
use App\Repository\ModelRepository;
use App\Entity\Utils\SluggableTrait;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Model
{
    use SluggableTrait;
    use EnableTrait;
    use DateTimeTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
