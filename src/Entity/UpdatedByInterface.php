<?php

declare(strict_types=1);

namespace App\Entity;

interface UpdatedByInterface
{
    public function getUpdatedBy(): ?User;

    public function setUpdatedBy(?User $user): self;
}
