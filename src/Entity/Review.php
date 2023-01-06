<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(
    repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(
        inversedBy: 'reviews')]
    #[ORM\JoinColumn(
        nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Votre e-mail est trop long, il ne doit pas faire plus de {{ limit }} caractères.')]

    #[Assert\NotBlank(
        message: 'L\'e-mail est nécessaire.')]
    #[Assert\Email(
        message: 'L\e-mail {{ value }} n\'est pas d\'un format valide.',
        // using the regex of the HTML5 email input element as validation
        mode: 'html5'
    )]
    private ?string $user_email = null;

    #[ORM\Column]
    #[Assert\Length(
        max: 50,
        maxMessage: 'Votre pseudo est trop long, il ne doit pas faire plus de {{ limit }} caractères.')]
    #[Assert\NotBlank(
        message: 'Le pseudo est nécessaire.')]
    private ?string $pseudo = null;

    #[ORM\Column]
    #[Assert\NotBlank(
        message: 'La note est nécessaire.')]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'Veuillez noter de 1 à 5.'
    )]
    private ?int $user_rating = null;

    #[ORM\Column(
        type: Types::TEXT)]
    #[Assert\Length(
        max: 1000,
        maxMessage: 'Votre commentaire est trop long, il ne doit pas faire plus de {{ limit }} caractères.')]
    #[Assert\NotBlank(
        message: 'Le commentaire est nécessaire.')]
    private ?string $comment = null;

    #[ORM\Column(
        nullable: true)]
    // The validation is in the ReviewType.php as the field of the form is not mapped to this entity.
    private ?string $picture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $submitDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->user_email;
    }

    public function setUserEmail(string $user_email): self
    {
        $this->user_email = $user_email;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getUserRating(): ?int
    {
        return $this->user_rating;
    }

    public function setUserRating(int $user_rating): self
    {
        $this->user_rating = $user_rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getSubmitDate(): ?\DateTimeInterface
    {
        return $this->submitDate;
    }

    public function setSubmitDate(\DateTimeInterface $submitDate): self
    {
        $this->submitDate = $submitDate;

        return $this;
    }
}
