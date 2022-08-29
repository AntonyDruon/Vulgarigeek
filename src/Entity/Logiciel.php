<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Commentairelogiciel;
use App\Repository\LogicielRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: LogicielRepository::class)]
#[Vich\Uploadable]
class Logiciel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'article_image', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\OneToMany(mappedBy: 'logiciel', targetEntity: Commentairelogiciel::class)]
    private Collection $commentairelogiciel;

    public function __construct()
    {
        $this->commentairelogiciel = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
   public function setImageFile(?File $imageFile = null): void
   {
       $this->imageFile = $imageFile;

       if (null !== $imageFile) {
           // It is required that at least one field changes if you are using doctrine
           // otherwise the event listeners won't be called and the file is lost
           $this->datedecreation = new \DateTimeImmutable();
       }
   }

   public function getImageFile(): ?File
   {
       return $this->imageFile;
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
            $commentairelogiciel->setLogiciel($this);
        }

        return $this;
    }

    public function removeCommentairelogiciel(Commentairelogiciel $commentairelogiciel): self
    {
        if ($this->commentairelogiciel->removeElement($commentairelogiciel)) {
            // set the owning side to null (unless already changed)
            if ($commentairelogiciel->getLogiciel() === $this) {
                $commentairelogiciel->setLogiciel(null);
            }
        }

        return $this;
    }
}
