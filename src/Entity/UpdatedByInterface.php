<?php


namespace App\Entity;


interface UpdatedByInterface
{
    public function getUpdatedBy(): ?User;

    public function setUpdatedBy(?User $user): self;
}