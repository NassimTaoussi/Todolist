<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @property TaskRepository|ObjectRepository $taskRepository
 * @property UserRepository|ObjectRepository $userRepository
 * @property ObjectManager $entityManager
 * @property KernelBrowser $client
 * @property object|Router|null $urlGenerator
 */
class SecurityControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        $this->client = static::createClient(); 
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function testDisplayPageLogin()
    {
        $this->client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAuthentificationSuccess()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form();

        $form['email'] = 'admin1@email.com';
        $form['password'] = '0000';

        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect('/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Bienvenue');
    }

    public function testAuthentificationError()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form();

        $form['email'] = 'admin1@email.com';
        $form['password'] = 'passworderror';

        $this->client->submit($form);
        $this->client->followRedirect('/login');
        $this->assertSelectorExists('label', 'Mot de passe');
    }

    public function testLogout()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin4@email.com']);
        $this->client->loginUser($user);
        $this->client->request('GET', '/logout');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect('/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Accueil');
    }
}