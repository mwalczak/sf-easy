<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Issue;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\IssueStatusEnum;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(): Response
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $projectId = $this->container->get('session')->get('project');
        $projectId = $this->container->get('request_stack')->getCurrentRequest()->get('project', $projectId);
        $this->container->get('session')->set('project', $projectId);

        $projects = $this->loadUserProjects($projectId);

        $project = $projectId ? $em->getRepository(Project::class)->find($projectId) : null;

        $criteria = new Criteria();
        $criteria
            ->where($criteria->expr()->eq('assignee', $this->getUser()))
            ->andWhere($criteria->expr()->notIn('status', [IssueStatusEnum::CLOSED, IssueStatusEnum::FIXED]))
            ->orderBy(['createdAt' => 'DESC']);

        return $this->render('dashboard/admin.html.twig', [
            'selected' => $projectId,
            'projects' => $projects,
            'myIssues' => $em->getRepository(Issue::class)->matching($criteria),
            'issues' => $em->getRepository(Issue::class)->findBy(['project' => $project], ['createdAt' => 'DESC']),
        ]);
    }

    /**
     * @return Project[]
     */
    private function loadUserProjects(?string &$projectId): array
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $projects = $this->getDoctrine()->getManager()->getRepository(Project::class)->findAll();
        } else {
            /** @var User $user */
            $user = $this->getUser();
            $projects = $user->getProjects()->toArray();
            if ($projectId) {
                $projectId = in_array($projectId, $user->getProjectsIds()) ? $projectId : $user->getProjectsIds()[0] ?? null;
            }
        }

        return $projects;
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sf Project');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Projects', 'fa fa-project-diagram', Project::class);
        yield MenuItem::linkToCrud('Issues', 'fa fa-bug', Issue::class);
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('css/admin.css')
            ->addJsFile('js/admin.js');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(): Crud
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->enableFilter();
        }

        return Crud::new();
//            ->showEntityActionsAsDropdown();
    }

    private function enableFilter(): void
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $projects = $user->getProjectsIdsWithAccess();
        $em->getFilters()->enable('project_filter')->setParameter('project', implode(',', $projects));
    }
}
