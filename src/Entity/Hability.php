<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use App\Entity\Sonata\Media;

#[ORM\Entity]
#[ORM\Table(name: 'habilities')]
class Hability implements TranslatableInterface, TimestampableInterface
{
    use TranslatableTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $layout;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Sonata\Media")]
    #[ORM\JoinColumn(name: 'image_id', referencedColumnName: 'id')]
    private ?Media $image;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Champion", inversedBy: 'habilities')]
    #[ORM\JoinColumn(name: 'champion_id', referencedColumnName: 'id')]
    private Champion $champion;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Sonata\Media")]
    #[ORM\JoinColumn(name: 'video_id', referencedColumnName: 'id')]
    private ?Media $video;

    /** @param array<mixed> $arguments */
    public function __call(string $method, array $arguments): mixed
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    public function __toString(): string
    {
        return $this->getName() ?? 'champion';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(?string $layout): static
    {
        $this->layout = $layout;

        return $this;
    }

    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function setImage(?Media $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getChampion(): ?Champion
    {
        return $this->champion;
    }

    public function setChampion(Champion $champion): static
    {
        $this->champion = $champion;

        return $this;
    }

    public function getVideo(): ?Media
    {
        return $this->video;
    }

    public function setVideo(?Media $video): static
    {
        $this->video = $video;

        return $this;
    }
}
