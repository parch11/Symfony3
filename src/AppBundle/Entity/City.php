<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * City
 *
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 */
class City
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\Column(type="text")
     */
    private $contactMessage;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="city")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName() : ? string
    {
        return $this->name;
    }

    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress() : ? string
    {
        return $this->address;
    }

    public function setAddress(string $address) : self
    {
        $this->address = $address;

        return $this;
    }

    public function getContactMessage() : ? string
    {
        return $this->contactMessage;
    }

    public function setContactMessage(string $contactMessage) : self
    {
        $this->contactMessage = $contactMessage;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts() : Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product) : self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCity($this);
        }

        return $this;
    }

    public function removeProduct(Product $product) : self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCity() === $this) {
                $product->setCity(null);
            }
        }

        return $this;
    }
}

