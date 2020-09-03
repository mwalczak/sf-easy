<?php

declare(strict_types=1);

namespace App\Entity;

interface UploadableInterface
{
    public function getIri(): string;

    public function setIri(?string $fake): self;

    public function getImages(): array;

    public function addImages(array $images): self;
}
