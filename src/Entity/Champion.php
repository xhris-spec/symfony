<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Sonata\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Entity]
#[ORM\Table(name: 'champions')]
class Champion implements TranslatableInterface, TimestampableInterface
{
    use TranslatableTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $slug;

    /**
     * @var string[]|null
     */
    #[ORM\Column(type: 'array', nullable: true)]
    private ?array $rol;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $active;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Sonata\Media", cascade: ["persist"])]
    #[ORM\JoinColumn(name: 'splashart_id', referencedColumnName: 'id')]
    private ?Media $splashart;

    /** @var Collection<int,Hability> */
    #[ORM\OneToMany(targetEntity: "App\Entity\Hability", mappedBy: 'champion', cascade: ['persist', 'remove'])]
    private Collection $habilities;

    public function __construct()
    {
        $this->habilities = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSplashart(): ?Media
    {
        return $this->splashart;
    }

    public function setSplashart(?Media $splashart): static
    {
        $this->splashart = $splashart;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getRol(): ?array
    {
        return $this->rol;
    }

    /**
     * @param string[]|null $rol
     * @return static
     */
    public function setRol(?array $rol): static
    {
        $this->rol = $rol;

        return $this;
    }
    /**
     * @return Collection<int, Hability>
     */
    public function getHabilities(): Collection
    {
        return $this->habilities;
    }

    public function addHability(Hability $hability): static
    {
        if (!$this->habilities->contains($hability)) {
            $this->habilities->add($hability);
            $hability->setChampion($this);
        }

        return $this;
    }

    public function removeHability(Hability $hability): static
    {
        $this->habilities->removeElement($hability);

        return $this;
    }
}
