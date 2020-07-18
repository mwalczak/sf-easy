<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected string $directory;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->directory = static::$kernel->getProjectDir();
        $this->entityManager = static::$container->get('doctrine')->getManager();
    }

    protected function loginAdmin(): void
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find(1);

        $this->client->loginUser($user);
    }

    protected function loginUser(): void
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find(2);

        $this->client->loginUser($user);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        unset($this->entityManager, $this->client);
    }
}
