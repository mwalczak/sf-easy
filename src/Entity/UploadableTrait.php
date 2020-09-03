<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Inflector\EnglishInflector;

trait UploadableTrait
{
    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $images = [];

    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param array|null $images
     * @return $this|UploadableInterface
     */
    public function setImages(?array $images): UploadableInterface
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @param array|null $images
     * @return $this|UploadableInterface
     */
    public function addImages(array $images): UploadableInterface
    {
        $this->images = array_merge($this->images, $images);

        return $this;
    }

    public function getIri(): string
    {
        $inflector = new EnglishInflector();

        return '/'.strtolower($inflector->pluralize((new \ReflectionClass(self::class))->getShortName())[0]).'/'.$this->id;
    }

    public function setIri(?string $fake): self
    {
        return $this;
    }
}
