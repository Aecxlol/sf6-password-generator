<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesControllerTest extends WebTestCase
{
    public function test_homepage_is_displayed_correctly(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Password generator');
        $this->assertPageTitleSame('Password Generator');
    }

    public function test_generated_password_page_is_displayed_correctly(): void
    {
        $client = static::createClient();
        $client->request('GET', '/generate-password');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Password generated ✔');
        $this->assertPageTitleSame('Generated password');
    }

    public function test_cookies_are_not_present_when_visiting_homepage_for_the_first_time(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertBrowserNotHasCookie('app_length');
        $this->assertBrowserNotHasCookie('app_uppercase_letters');
        $this->assertBrowserNotHasCookie('app_digits');
        $this->assertBrowserNotHasCookie('app_special_characters');
    }

    public function test_cookies_are_set_when_generating_new_password(): void
    {
        $client = static::createClient();
        $client->request('GET', '/generate-password');

        $this->assertBrowserHasCookie('app_length');
        $this->assertBrowserHasCookie('app_uppercase_letters');
        $this->assertBrowserHasCookie('app_digits');
        $this->assertBrowserHasCookie('app_special_characters');
    }

    public function test_password_generation_from_form_should_work()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $crawler = $client->submitForm('generate-password-btn', [], 'GET');

        $this->assertRouteSame('app_generate_password');
        $this->assertSame(12, mb_strlen($crawler->filter('.alert.alert-success > strong')->text()));

        $client->clickLink('« Go back to homepage');
        $this->assertRouteSame('app_home');
    }

    public function test_password_generation_from_form_with_values_should_work()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $crawler = $client->submitForm('generate-password-btn', [
            'length' => 15,
            'uppercase-letters' => false,
            'digits' => true,
            'special-characters' => true
        ], 'GET');

        $this->assertRouteSame('app_generate_password');
        $this->assertSame(15, mb_strlen($crawler->filter('.alert.alert-success > strong')->text()));

        $crawler = $client->clickLink('« Go back to homepage');
        $this->assertRouteSame('app_home');

        $this->assertSame(15, (int)$crawler->filter('select[name="length"] > option[selected]')->attr('value'));
        $this->assertCheckboxNotChecked('uppercase-letters');
        $this->assertCheckboxChecked('digits');
        $this->assertCheckboxChecked('special-characters');

        $this->assertBrowserCookieValueSame('app_length', '15');
        $this->assertBrowserCookieValueSame('app_uppercase_letters', '0');
        $this->assertBrowserCookieValueSame('app_digits', '1');
        $this->assertBrowserCookieValueSame('app_special_characters', '1');
    }

    public function test_password_min_length_should_be_8()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/generate-password?length=2');

//        $crawler = $client->submitForm('generate-password-btn', [
//            'length' => 2
//        ], 'GET');

        $this->assertRouteSame('app_generate_password');

        $this->assertSame(8, mb_strlen($crawler->filter('.alert.alert-success > strong')->text()));
    }

    public function test_password_max_length_should_be_60()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/generate-password?length=60');

//        $crawler = $client->submitForm('generate-password-btn', [
//            'length' => 200
//        ], 'GET');

        $this->assertRouteSame('app_generate_password');

        $this->assertSame(60, mb_strlen($crawler->filter('.alert.alert-success > strong')->text()));
    }
}
