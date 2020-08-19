<?php

declare(strict_types=1);

namespace App\Entity;

interface ProjectComponentInterface
{
    public function getProject(): ?Project;

    public function setProject(?Project $project): self;
}
