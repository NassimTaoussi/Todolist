<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property TaskRepository|ObjectRepository $taskRepository
 * @property UserRepository|ObjectRepository $userRepository
 * @property ObjectManager $entityManager
 * @property KernelBrowser $client
 * @property object|Router|null $urlGenerator
 */
class TaskControllerTest extends WebTestCase
{

    public function setUp(): void
    {
        $this->client = static::createClient(); 
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    // Test création d'une tâche quand utilisateur connecté
    public function testCreateTaskUserLogged() 
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin1@email.com']);
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Titre test';
        $form['task[content]'] = 'Contenu test';

        $this->client->submit($form);
        $this->client->followRedirect('/tricks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des tâches');
    }

    // Test création d'une tâche quand formulaire non conforme
    public function testCreateTaskUserLoggedDontWorking() 
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin1@email.com']);
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Titre test';
        $form['task[content]'] = '';

        $this->client->submit($form);
    }

    // Test création d'une tâche quand utilisateur non connecté
    public function testCreateTaskUserNotLogged()
    {
        $this->client->request('GET', '/tasks/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect('/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('label', 'Mot de passe');
    }

    public function testDisplayTasksList(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin1@email.com']);
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertCount(21, $crawler->filter(".task"));
    }


    // Test edition d'un tâche quand utilisateur connecter
    public function testEditTaskUserLogged()
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin1@email.com']);
        $this->client->loginUser($admin);

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['author' => null])->getId();

        $crawler = $this->client->request('GET', '/tasks/edit/' . $task); 

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Titre test edit';
        $form['task[content]'] = 'Contenu test edit';
        
        $this->client->submit($form);
        $this->client->followRedirect('/tricks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des tâches');
    }

    // Test edition d'un tâche quand utilisateur non connecter
    public function testEditTaskUserNotLogged()
    {
        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['author' => null])->getId();

        $this->client->request('GET', '/tasks/edit/' . $task); 
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect('/tricks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des tâches');
    }

    // Test tâche marquer comme faite quand utilisateur connecter
    public function testToggleTaskUserLogged()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin1@email.com']);
        $this->client->loginUser($user);

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['author' => null])->getId();

        $this->client->request('GET', '/tasks/toggle/' . $task);
        $this->client->followRedirect('/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des tâches');
    }

    // Test tâche marquer comme faite quand utilisateur non connecter
    public function testToggleTaskUserNotLogged()
    {
        $this->client->request('GET', '/tasks/toggle/3');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect('/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des tâches');
    }


    // Test suppression d'une tâche quand utilisateur non connecté
    public function testRemoveTaskUserNotLogged()
    {
        $this->client->request('GET', '/tasks/delete/5');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect('/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('label', 'Mot de passe');
    }

    // Test suppression d'une tâche par son auteur
    public function testRemoveTaskByTheAuthor()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin1@email.com']);
        $this->client->loginUser($user);

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['author' => $user->getId()])->getId();

        $this->client->request('GET', '/tasks/delete/' . $task); 
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $this->client->followRedirect('/tricks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des tâches');
    }

    // Test suppression d'une tâche par un autre utilisateur
    public function testRemoveTaskByNotTheAuthor()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'username1@email.com']);
        $this->client->loginUser($user);
        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['author' => null])->getId();

        $this->client->request('GET', '/tasks/delete/' . $task); 

        $this->client->followRedirect('/tricks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des tâches');
    }

    // Test suppression d'une tâche par un autre utilisateur
    public function testRemoveTaskByAdmin()
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin1@email.com']);
        $this->client->loginUser($admin);

        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['author' => null])->getId();

        $this->client->request('GET', '/tasks/delete/' . $task); 

        $this->client->followRedirect('/tricks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h1', 'Liste des tâches');
    }
}