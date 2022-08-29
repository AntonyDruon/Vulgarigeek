<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Commentairearticle;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;



#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'article_image', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedecreation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateedition = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Commentairearticle::class)]
    private Collection $Commentairearticle;

    public function __construct()
    {
        $this->Commentairearticle = new ArrayCollection();
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;

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


    public function getDatedecreation(): ?\DateTimeInterface
    {
        return $this->datedecreation;
    }

    public function setDatedecreation(\DateTimeInterface $datedecreation): self
    {
        $this->datedecreation = $datedecreation;

        return $this;
    }

    public function getDateedition(): ?\DateTimeInterface
    {
        return $this->dateedition;
    }

    public function setDateedition(?\DateTimeInterface $dateedition): self
    {
        $this->dateedition = $dateedition;

        return $this;
    }

    /**
     * @return Collection<int, Commentairearticle>
     */
    public function getCommentairearticle(): Collection
    {
        return $this->Commentairearticle;
    }

    public function addCommentairearticle(Commentairearticle $Commentairearticle): self
    {
        if (!$this->Commentairearticle->contains($Commentairearticle)) {
            $this->Commentairearticle[] = $Commentairearticle;
            $Commentairearticle->setArticle($this);
        }

        return $this;
    }

    public function removeCommentairearticle(Commentairearticle $Commentairearticle): self
    {
        if ($this->Commentairearticle->removeElement($Commentairearticle)) {
            // set the owning side to null (unless already changed)
            if ($Commentairearticle->getArticle() === $this) {
                $Commentairearticle->setArticle(null);
            }
        }

        return $this;
    }

   
}
