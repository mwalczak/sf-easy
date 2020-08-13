<?php

namespace App\Entity;

interface ProjectComponentInterface
{
    public function getProject(): ?Project;

    public function setProject(?Project $project): self;
}
