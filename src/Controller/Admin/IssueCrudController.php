<?php

namespace App\Controller\Admin;

use App\Entity\Issue;
use App\Enum\IssuePriorityEnum;
use App\Enum\IssueStatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class IssueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Issue::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('project')->setCrudController(ProjectCrudController::class),
            TextField::new('summary'),
            ChoiceField::new('status')->setChoices(array_flip(IssueStatusEnum::getAvailableNames()))->onlyWhenUpdating(),
            ChoiceField::new('priority')->setChoices(array_flip(IssuePriorityEnum::getAvailableNames())),
            TextareaField::new('stepsToReproduce'),
            TextareaField::new('description'),
            AssociationField::new('assignee')->setCrudController(UserCrudController::class),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->onlyOnDetail(),
            TextField::new('updatedBy')->onlyOnDetail(),
        ];
    }
}
