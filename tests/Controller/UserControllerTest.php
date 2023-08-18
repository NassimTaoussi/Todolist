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
class UserControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        $this->client = static::createClient(); 
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function testUserListNotLogged()
    {
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect('/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('label', 'Mot de passe');
    }

    public function testUserListLoggedWhenRoleUser()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username1@email.com']);
        $this->client->loginUser($user);
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN); 
    }

    public function testUserListLoggedWhenRoleAdmin()
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin1@email.com']);
        $this->client->loginUser($admin);
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateUserWhenUser()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username1@email.com']);
        $this->client->loginUser($user);
        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testCreateUserWhenAdmin()
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin1@email.com']);
        $this->client->loginUser($admin);
        $crawler = $this->client->request('GET', '/users/create');
        
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'Username Test';
        $form['user[email]'] = 'email-test@email.com';
        $form['user[password]'] = '0000';
        $form['user[role]'] = 'ROLE_USER';

        $this->client->submit($form);
        $this->client->followRedirect('/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des utilisateurs');
    }

    public function testEditUserWhenUser()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username1@email.com']);
        $this->client->loginUser($user);

        $userEdit = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username2@email.com'])->getId();
        $this->client->request('GET', '/users/edit/' . $userEdit);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testEditUserWhenNotLogged()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username4@email.com'])->getId();
        $this->client->request('GET', "/users/edit/" . $user);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect('/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('label', 'Mot de passe');
    }

    public function testEditUserWhenAdmin()
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin2@email.com']);
        $this->client->loginUser($admin);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username2@email.com'])->getId();
        $crawler = $this->client->request('GET', '/users/edit/' . $user);
        dump('Test 1');
        $form = $crawler->selectButton('Modifier')->form();
        dump('Test 2');
        $form['user[username]'] = 'Username Test Edit';
        $form['user[email]'] = 'email-test-edit@email.com';
        $form['user[password]'] = '0000';
        $form['user[role]'] = 'ROLE_ADMIN';

        $this->client->submit($form);
        $this->client->followRedirect('/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des utilisateurs');
    }

    public function testDeleteUserWhenUser()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username10@email.com']);
        $this->client->loginUser($user);

        $userEdit = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username12@email.com'])->getId();
        $this->client->request('GET', '/users/delete/' . $userEdit);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteUserWhenNotLogged()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username6@email.com'])->getId();
        $this->client->request('GET', "/users/delete/" . $user);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect('/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('label', 'Mot de passe');
    }

    public function testDeletetUserWhenAdmin()
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin2@email.com']);
        $this->client->loginUser($admin);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username18@email.com'])->getId();
        $crawler = $this->client->request('GET', '/users/delete/' . $user);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect('/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des utilisateurs');
    }
}