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
class HomeControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        $this->client = static::createClient(); 
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function testHomepageWhenNotLogged()
    {
        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Accueil');
    }

    public function testHomepageWhenLogged()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username1@email.com']);
        $this->client->loginUser($user);
        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Bienvenue');
    }
}