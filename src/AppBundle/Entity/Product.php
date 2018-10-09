<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 * @Vich\Uploadable
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */

class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    //--------------------------
    // -- VICH UPLOADER START -- 
    //--------------------------

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     *  @Assert\Image(
     *     maxSize = "1900k",
     *     mimeTypes = {"image/jpeg", "image/png"},
     *     mimeTypesMessage = "Mauvais format d'image",
     *     maxSizeMessage = "L'image de doit pas dépasser 1.9Mo"
     * )
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;
    //--------------------------
    // -- VICH UPLOADER END ---- 
    //--------------------------

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $autoDeleteAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="products")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle() : ? string
    {
        return $this->title;
    }

    public function setTitle(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription() : ? string
    {
        return $this->description;
    }

    public function setDescription(? string $description) : self
    {
        $this->description = $description;

        return $this;
    }


    public function getRef() : ? string
    {
        return $this->ref;
    }

    public function setRef(string $ref) : self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getCreatedAt() : ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAutoDeleteAt() : ? \DateTimeInterface
    {
        return $this->autoDeleteAt;
    }

    public function setAutoDeleteAt(\DateTimeInterface $autoDeleteAt) : self
    {
        $this->autoDeleteAt = $autoDeleteAt;

        return $this;
    }

    public function getCity() : ? City
    {
        return $this->city;
    }

    public function setCity(? City $city) : self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags() : Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag) : self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag) : self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getCategory() : ? Category
    {
        return $this->category;
    }

    public function setCategory(? Category $category) : self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser() : ? User
    {
        return $this->user;
    }

    public function setUser(? User $user) : self
    {
        $this->user = $user;

        return $this;
    }

    //--------------------------
    // -- VICH UPLOADER START -- 
    //--------------------------

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
    public function getUpdatedAt() : ? \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt) : self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    //--------------------------
    // -- VICH UPLOADER END ---- 
    //--------------------------
}

