<?php

namespace App\Controller\Admin;

use App\Entity\Issue;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IssueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Issue::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
