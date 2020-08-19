<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\DomCrawler\Crawler;

class UserControllerTest extends AppTestCase
{
    public function testCreateUserAsAdmin(): void
    {
        $crawler = $this->loginAdminAndClickNewUser();

        $this->submitUserFormWithData($crawler, 'new@user.dev', 'p@ssw0rd');

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->assertNotNull($this->entityManager->getRepository(User::class)->findOneBy(['email' => 'new@user.dev']));
    }

    public function testCreateUserAsAdminWithShortPassword(): void
    {
        $crawler = $this->loginAdminAndClickNewUser();

        $this->submitUserFormWithData($crawler, 'new@user.dev', 'short');

        $this->assertFalse($this->client->getResponse()->isRedirect());

        $this->assertNull($this->entityManager->getRepository(User::class)->findOneBy(['email' => 'new@user.dev']));
    }

    public function testNoUsersMenuWhenLoggedAsUser(): void
    {
        $this->loginUser();

        $crawler = $this->client->request('GET', '/admin');

        $this->assertSame(0, $crawler->selectLink('Users')->count());
    }

    private function loginAdminAndClickNewUser(): Crawler
    {
        $this->loginAdmin();

        $crawler = $this->client->request('GET', '/admin');

        $this->assertSame(1, $crawler->selectLink('Users')->count());

        $this->client->clickLink('Users');

        $this->assertResponseStatusCodeSame(200);

        $crawler = $this->client->clickLink('Add User');

        $this->assertResponseStatusCodeSame(200);

        return $crawler;
    }

    private function submitUserFormWithData(Crawler $crawler, string $email, string $password): void
    {
        $form = $crawler->selectButton('Create')->form();
        $form['User[email]'] = $email;
        $form['User[plainPassword]'] = $password;
        $this->client->submit($form);
    }
}
