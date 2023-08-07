<?php

namespace App\Tests\Controller;

use App\Entity\User;
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

    public function testDisplayTasksList(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'nassim28500@hotmail.fr']);
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2',"Liste de l'ensemble de vos tâches" );
        $this->assertCount(4, $crawler->filter("tr.task"));
    }

    /*
    
    public function testDisplayTasksTodoList(): void
    {
        $this->client->request('GET', '/tasks/todo');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2',"Liste de l'ensemble de vos tâches à faire" );
    }
    
    
    public function testDisplayTasksDoneList(): void
    {
        $this->client->request('GET', '/tasks/done');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2',"Liste de l'ensemble de vos tâches terminées" );
    }
    
    */

    public function testShouldRaiseHttpAccessDeniedAndRedirectToLogin(): void
    {
        $this->client->request(Request::METHOD_GET, '/tasks/create');
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseRedirects('http://localhost/login');
    }

    public function testCreateTask(): void 
    {

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'nassim28500@hotmail.fr']);

        $this->client->loginUser($user);

        $crawler = $this->client->request(Request::METHOD_GET, '/tasks/create');
        self::assertResponseIsSuccessful();

        /** @@phpstan-ignore-next-line */
        $token = $crawler
            ->filter('form[name=task]')
            ->form()
            ->get('task')['_token']
            ->getValue();

        $formData = self::createFormData();

        $formData['parameters']['task']['_token'] = $token;

        $this->client->request(Request::METHOD_POST, '/tasks/create', $formData['parameters']);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);

    }

    public function testCreateTaskDontWorking(): void
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'nassim28500@hotmail.fr']);

        $this->client->loginUser($user);

        $crawler = $this->client->request(Request::METHOD_GET, '/tasks/create');
        self::assertResponseIsSuccessful();

        /** @@phpstan-ignore-next-line */
        $token = $crawler
            ->filter('form[name=task]')
            ->form()
            ->get('task')['_token']
            ->getValue();

        $formData = self::createFormData();

        $formData['parameters']['task']['_token'] = $token;

        $this->client->request(Request::METHOD_POST, '/tasks/create', $formData['parameters']);

        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    private static function createFormData(
        string $title = 'Titre',
        string $content = 'Description',
    ): array {
        return [
            'parameters' => [
                'task' => [
                    'titre' => $title,
                    'description' => $content,
                ],
            ],
        ];
    }
}