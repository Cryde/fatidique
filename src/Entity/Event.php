<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;
    /**
     * @var \DateTimeInterface
     *
     * @Assert\GreaterThan("now")
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $date;
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private string $label;
    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private bool $private;
    /**
     * @var string
     *
     * @ORM\Column(length=150, unique=true)
     */
    private string $slug;
    /**
     * @var \DateTimeInterface $created
     *
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $created;
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private bool $isDateOnly;

    public function __construct()
    {
        $this->id = 0;
        $this->created = new \DateTimeImmutable();
        $this->date = new \DateTimeImmutable();
        $this->label = '';
        $this->slug = '';
        $this->description = '';
        $this->private = false;
        $this->isDateOnly = false;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get date
     *
     * @return \DateTimeInterface
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \DateTimeInterface $date
     *
     * @return Event
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Event
     */
    public function setLabel($label): Event
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description): Event
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Event
     */
    public function setSlug($slug): Event
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * Set private
     *
     * @param boolean $private
     *
     * @return Event
     */
    public function setPrivate($private): Event
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTimeInterface
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param \DateTimeInterface $created
     *
     * @return Event
     */
    public function setCreated($created): Event
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get isDateOnly
     *
     * @return bool
     */
    public function isDateOnly()
    {
        return $this->isDateOnly;
    }

    /**
     * Set isDateOnly
     *
     * @param bool $isDateOnly
     *
     * @return Event
     */
    public function setIsDateOnly($isDateOnly): Event
    {
        $this->isDateOnly = $isDateOnly;

        return $this;
    }
}
