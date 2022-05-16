<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesControllerTest extends WebTestCase
{
    public function test_homepage_should_work(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Password generator');
        $this->assertPageTitleSame('Password Generator');
    }

    public function test_generated_password_should_work(): void
    {
        $client = static::createClient();
        $client->request('GET', '/generate-password');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Password generated ✔');
        $this->assertPageTitleSame('Generated password');
    }
}
