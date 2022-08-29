<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Commentaireapplication;
use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[Vich\Uploadable]
class Application
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

    #[ORM\OneToMany(mappedBy: 'application', targetEntity: Commentaireapplication::class)]
    private Collection $Commentaireapplication;

    public function __construct()
    {
        $this->Commentaireapplication = new ArrayCollection();
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
     * @return Collection<int, Commentaireapplication>
     */
    public function getCommentaireapplication(): Collection
    {
        return $this->Commentaireapplication;
    }

    public function addCommentaireapplication(Commentaireapplication $Commentaireapplication): self
    {
        if (!$this->Commentaireapplication->contains($Commentaireapplication)) {
            $this->Commentaireapplication[] = $Commentaireapplication;
            $Commentaireapplication->setApplication($this);
        }

        return $this;
    }

    public function removeCommentaireapplication(Commentaireapplication $Commentaireapplication): self
    {
        if ($this->Commentaireapplication->removeElement($Commentaireapplication)) {
            // set the owning side to null (unless already changed)
            if ($Commentaireapplication->getApplication() === $this) {
                $Commentaireapplication->setApplication(null);
            }
        }

        return $this;
    }
}
