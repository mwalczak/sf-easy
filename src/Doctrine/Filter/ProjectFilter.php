<?php

declare(strict_types=1);

namespace App\Doctrine\Filter;

use App\Entity\Project;
use App\Entity\ProjectComponentInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ProjectFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (Project::class === $targetEntity->getReflectionClass()->getName()) {
            return $targetTableAlias.'.id IN ('.$this->getParameter('project').')';
        } elseif ($targetEntity->getReflectionClass()->implementsInterface(ProjectComponentInterface::class)) {
            return $targetTableAlias.'.project_id IN ('.$this->getParameter('project').')';
        }

        return '';
    }
}
