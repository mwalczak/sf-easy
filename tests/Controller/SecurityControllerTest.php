<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class SecurityControllerTest extends AppTestCase
{
    public function testOpenHomePageAsAnonymous(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseRedirects('/admin');
    }

    public function testOpenAdminAsAnonymous(): void
    {
        $this->client->request('GET', '/admin');

        $this->assertResponseRedirects('/login');
    }

    public function testOpenAdminAsAdmin(): void
    {
        $this->loginAdmin();

        $this->client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testLogout(): void
    {
        $this->loginAdmin();

        $this->client->request('GET', '/logout');

        $this->assertTrue($this->client->getResponse()->isRedirect());
    }
}
