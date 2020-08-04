<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface UpdatedByInterface
{
    public function getUpdatedBy(): ?UserInterface;

    public function setUpdatedBy(?UserInterface $user): self;
}
