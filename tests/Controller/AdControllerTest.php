<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdControllerTest extends WebTestCase
{
    public function testHomePageHeader(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Strona główna');
    }

    public function testSubmitAddForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ad/add');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Dodawanie ogłoszenia');

        $crawler = $client->submitForm('Dodaj ogłoszenie');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('form li', 'Nazwa produktu musi zawierać co najmniej 2 znaki');
    }
}
