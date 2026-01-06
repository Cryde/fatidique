<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan('now')]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $private = null;

    #[ORM\Column(length: 150, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $created = null;

    #[ORM\Column]
    private ?bool $isDateOnly = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $theme = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $author = null;

    /** @var Collection<int, EventLike> */
    #[ORM\OneToMany(targetEntity: EventLike::class, mappedBy: 'event', cascade: ['remove'])]
    private Collection $likes;

    /** @var Collection<int, Comment> */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'event', cascade: ['remove'])]
    private Collection $comments;

    public const THEMES = [
        'default' => ['label' => 'Classique', 'emoji' => '⏱️', 'particle' => '✦'],
        'party' => ['label' => 'Fête', 'emoji' => '🎉', 'particle' => '✨'],
        'romantic' => ['label' => 'Romantique', 'emoji' => '💕', 'particle' => '💗'],
        'professional' => ['label' => 'Professionnel', 'emoji' => '💼', 'particle' => '•'],
        'spooky' => ['label' => 'Halloween', 'emoji' => '🎃', 'particle' => '🦇'],
        'tropical' => ['label' => 'Tropical', 'emoji' => '🌴', 'particle' => '🌺'],
        'christmas' => ['label' => 'Noël', 'emoji' => '🎄', 'particle' => '❄️'],
        'rainy' => ['label' => 'Jour de pluie', 'emoji' => '🌧️', 'particle' => '💧'],
    ];

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->date = new \DateTime();
        $this->label = '';
        $this->slug = '';
        $this->description = '';
        $this->private = false;
        $this->isDateOnly = false;
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): static
    {
        $this->private = $private;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function isIsDateOnly(): ?bool
    {
        return $this->isDateOnly;
    }

    public function setIsDateOnly(bool $isDateOnly): static
    {
        $this->isDateOnly = $isDateOnly;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getThemeOrDefault(): string
    {
        return $this->theme ?? 'default';
    }

    /**
     * @return Collection<int, EventLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function getLikesCount(): int
    {
        return $this->likes->count();
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getCommentsCount(): int
    {
        return $this->comments->count();
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }
}
