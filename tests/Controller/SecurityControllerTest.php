<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class SecurityControllerTest extends AppTestCase
{
    public function testOpenHomePageAsAnonymous(): void
    {
        $this->client->request('GET', '/');

        $this->assertTrue($this->client->getResponse()->isRedirect('/admin'));
    }

    public function testOpenAdminAsAnonymous(): void
    {
        $this->client->request('GET', '/admin');

        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
    }

    public function testLogout(): void
    {
        $this->client->request('GET', '/logout');

        $this->assertTrue($this->client->getResponse()->isRedirect());
    }
}
