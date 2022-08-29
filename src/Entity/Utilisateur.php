<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Commentairearticle;
use App\Entity\Commentairelogiciel;
use App\Entity\Commentaireapplication;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Commentairearticle::class)]
    private Collection $commentairearticle;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Commentaireapplication::class)]
    private Collection $commentaireapplication;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Commentairelogiciel::class)]
    private Collection $commentairelogiciel;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function __construct()
    {
        $this->commentairearticle = new ArrayCollection();
        $this->commentaireapplication = new ArrayCollection();
        $this->commentairelogiciel = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection<int, commentairearticle>
     */
    public function getCommentairearticle(): Collection
    {
        return $this->commentairearticle;
    }

    public function addCommentairearticle(Commentairearticle $commentairearticle): self
    {
        if (!$this->commentairearticle->contains($commentairearticle)) {
            $this->commentairearticle[] = $commentairearticle;
            $commentairearticle->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentairearticle(commentairearticle $commentairearticle): self
    {
        if ($this->commentairearticle->removeElement($commentairearticle)) {
            // set the owning side to null (unless already changed)
            if ($commentairearticle->getUtilisateur() === $this) {
                $commentairearticle->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, commentaireapplication>
     */
    public function getCommentaireapplication(): Collection
    {
        return $this->commentaireapplication;
    }

    public function addCommentaireapplication(Commentaireapplication $commentaireapplication): self
    {
        if (!$this->commentaireapplication->contains($commentaireapplication)) {
            $this->commentaireapplication[] = $commentaireapplication;
            $commentaireapplication->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentaireapplication(Commentaireapplication $commentaireapplication): self
    {
        if ($this->commentaireapplication->removeElement($commentaireapplication)) {
            // set the owning side to null (unless already changed)
            if ($commentaireapplication->getUtilisateur() === $this) {
                $commentaireapplication->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, commentairelogiciel>
     */
    public function getCommentairelogiciel(): Collection
    {
        return $this->commentairelogiciel;
    }

    public function addCommentairelogiciel(Commentairelogiciel $commentairelogiciel): self
    {
        if (!$this->commentairelogiciel->contains($commentairelogiciel)) {
            $this->commentairelogiciel[] = $commentairelogiciel;
            $commentairelogiciel->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentairelogiciel(Commentairelogiciel $commentairelogiciel): self
    {
        if ($this->commentairelogiciel->removeElement($commentairelogiciel)) {
            // set the owning side to null (unless already changed)
            if ($commentairelogiciel->getUtilisateur() === $this) {
                $commentairelogiciel->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
