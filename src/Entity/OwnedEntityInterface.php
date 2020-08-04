<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface OwnedEntityInterface
{
    public function getOwner(): ?User;

    public function setOwner(?UserInterface $owner): self;
}
