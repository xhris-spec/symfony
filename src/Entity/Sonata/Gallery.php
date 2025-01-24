<?php

declare(strict_types=1);

namespace App\Entity\Sonata;

use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Entity\BaseGallery;

/**
 * @phpstan-template T of GalleryItem
 *
 * @phpstan-extends BaseGallery<GalleryItem>
 */
#[ORM\Entity]
#[ORM\Table(name: "media__gallery")]
class Gallery extends BaseGallery
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
