<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    #[Groups(['show'])]
    private string $id;

    /**
     * @ORM\Column(type="string", length=512)
     */
    #[Assert\NotBlank]
    #[Assert\Length(max: 512)]
    #[Groups(['show', 'create'])]
    private string $text;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    #[Groups(['show'])]
    private User $author;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="message", orphanRemoval=true, fetch="EAGER")
     */
    #[Groups(['show'])]
    private Collection $likes;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['show'])]
    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function setLikes(Collection $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function onCreate(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
