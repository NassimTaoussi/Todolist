<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/create', name: 'create_user')]
    #[IsGranted("ROLE_ADMIN")]
    public function createUser(Request $request, UserManager $userManager): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->add($user);
            $this->addFlash('success', 'Vous venez de créé un nouvelle utilisateur');

            return $this->redirectToRoute('users');
        }


        return $this->render('user/create_user.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/users/edit/{id}', name: 'edit_user')]
    #[IsGranted("ROLE_ADMIN")]
    public function editUser(int $id, UserRepository $userRepository, Request $request, UserManager $userManager): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->update($user);
            $this->addFlash('success', 'Modification appliquer');

            return $this->redirectToRoute('users');
        }

        return $this->render('user/edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/users/delete/{id}', name: 'delete_user')]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteUser(int $id, UserRepository $userRepository): Response
    {
        $userRepository->deleteOneUserById($id);

        return $this->redirectToRoute('users');

    }
}
