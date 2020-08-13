<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Enum\ProjectStatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            yield AssociationField::new('owner')->setCrudController(UserCrudController::class);
        }
        yield TextField::new('name');
        yield ChoiceField::new('status')->setChoices(array_flip(ProjectStatusEnum::getAvailableNames()))->onlyWhenUpdating();
        yield BooleanField::new('isQAEnabled');
        yield DateTimeField::new('createdAt')->hideOnForm();
        yield DateTimeField::new('updatedAt')->hideOnForm();
        yield TextField::new('updatedBy')->onlyOnDetail();
        yield TextField::new('hash')->onlyOnDetail();
    }
}
