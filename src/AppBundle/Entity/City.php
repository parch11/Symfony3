<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

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
     * The internal primary identity key.
     *
     * @var UuidInterface|null
     *
     * @ORM\Column(type="uuid", unique=true)
     */

    private $uuid;

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
        $this->setUuid();
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
    public function getUuid()
    {
        return $this->uuid;
    }
    private function setUuid()
    {
        try {
            // Generate a version 4 (random) UUID object
            $uuid4 = Uuid::uuid4();
            $this->uuid = $uuid4->toString();
        } catch (UnsatisfiedDependencyException $e) {
            // Some dependency was not met. Either the method cannot be called on a
            // 32-bit system, or it can, but it relies on Moontoast\Math to be present.
            throw new HttpException(500, 'Caught exception: ' . $e->getMessage());
        }
    }
}

