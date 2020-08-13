<?php

declare(strict_types=1);

namespace App\Doctrine\Filter;

use App\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class UserFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (User::class === $targetEntity->getReflectionClass()->getName()) {
            return $targetTableAlias.'.id IN ('.trim($this->getParameter('user'), "'").')';
        }

        return '';
    }
}
