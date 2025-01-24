<?php

declare(strict_types=1);

namespace App\Entity\Sonata;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

#[ORM\Entity]
#[ORM\Table(name: "sonata_users")]
class User extends BaseUser
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    protected $id;

    public function getId(): ?int
    {
        return (int) $this->id;
    }
}
