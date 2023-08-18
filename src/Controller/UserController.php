<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;
use App\Manager\UserManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    #[Route('/users', name: 'users')]
    #[IsGranted("ROLE_ADMIN")]
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/create', name: 'create_user')]
    #[IsGranted("ROLE_ADMIN")]
    public function createUser(Request $request, UserManagerInterface $userManagerInterface): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManagerInterface->add($user);
            $this->addFlash('success', 'Vous venez de créé un nouvelle utilisateur');

            return $this->redirectToRoute('users');
        }


        return $this->render('user/create_user.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/users/edit/{id}', name: 'edit_user')]
    #[IsGranted("ROLE_ADMIN")]
    public function editUser(User $user, Request $request, UserManagerInterface $userManagerInterface): Response
    {

        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManagerInterface->update($user);
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
    public function deleteUser(User $user, UserManagerInterface $userManagerInterface): Response
    {
        $userManagerInterface->delete($user);

        return $this->redirectToRoute('users');

    }
}
