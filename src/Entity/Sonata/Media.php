<?php

declare(strict_types=1);

namespace App\Entity\Sonata;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Entity\BaseMedia;
use Sonata\MediaBundle\Model\GalleryItemInterface;

#[ORM\Entity]
#[ORM\Table(name: "media__media")]
class Media extends BaseMedia
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    protected ?int $id = null;

    /** @var Collection<int,GalleryItemInterface> */
    protected Collection $galleryItems;

    public function __construct()
    {
        parent::__construct();
        $this->galleryItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, GalleryItemInterface>
     */
    public function getGalleryItems(): Collection
    {
        return $this->galleryItems;
    }

    public function addGalleryItem(GalleryItem $galleryItem): self
    {
        if (!$this->galleryItems->contains($galleryItem)) {
            $this->galleryItems->add($galleryItem);
            $galleryItem->setMedia($this);
        }

        return $this;
    }

    public function removeGalleryItem(GalleryItem $galleryItem): self
    {
        // set the owning side to null (unless already changed)
        if ($this->galleryItems->removeElement($galleryItem) && $galleryItem->getMedia() === $this) {
            $galleryItem->setMedia(null);
        }

        return $this;
    }
}
