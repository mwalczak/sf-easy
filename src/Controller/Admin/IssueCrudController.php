<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Issue;
use App\Enum\IssuePriorityEnum;
use App\Enum\IssueStatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IssueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Issue::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('project')->setCrudController(ProjectCrudController::class)->addCssClass('project_filter')->onlyWhenCreating()->onlyOnDetail()->onlyOnIndex(),
            TextField::new('summary'),
            ChoiceField::new('status')->setChoices(array_flip(IssueStatusEnum::getAvailableNames()))->onlyWhenUpdating(),
            ChoiceField::new('priority')->setChoices(array_flip(IssuePriorityEnum::getAvailableNames())),
            TextareaField::new('stepsToReproduce'),
            TextareaField::new('description'),
            AssociationField::new('assignee')->setCrudController(UserCrudController::class)->addCssClass('project_filter_target'),
            DateTimeField::new('createdAt')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->onlyOnDetail(),
            TextField::new('updatedBy')->onlyOnDetail(),
        ];
    }

    /**
     * @Route("/issues/{id}/close", name="issue_close")
     */
    public function close(Issue $issue): Response
    {
        $this->denyAccessUnlessGranted('edit', $issue);

        $issue->setStatus(IssueStatusEnum::CLOSED);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_dashboard');
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addJsFile('js/admin.js')
            ;
    }
}
