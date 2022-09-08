<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testHomePageIsWorking(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Ser!es');
    }

    public function testAddSerieRedirectIfUserIsNotLog()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/series/add');

        $this->assertResponseRedirects('/login', 302);
    }

    public function testAddSerieIsWorkingIsUserIsLogged()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'kylian.virolle2021@campus-eni.fr']);

        $client->loginUser($user);

        $crawler = $client->request('GET', '/series/add');

        $this->assertResponseIsSuccessful();
    }

    public function testAccountCreation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $client->submitForm('Register', [
            'registration_form[email]' => 'john@doe.fr',
            'registration_form[firstname]' => 'John',
            'registration_form[lastname]' => 'Doe',
            'registration_form[plainPassword][first]' => 'Pa$$w0rd',
            'registration_form[plainPassword][second]' => 'Pa$$w0rd'
        ]);

        $this->assertResponseRedirects('/series', 302);
    }
}
