<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Product
 *
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

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

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

    public function getImage() : ? string
    {
        return $this->image;
    }

    public function setImage(? string $image) : self
    {
        $this->image = $image;

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
}

