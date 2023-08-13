<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter {

    public const TASK_EDIT = 'editTask';
    public const TASK_DELETE = 'deleteTask';
    public const TASK_TOGGLE = 'toggleTask';
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::TASK_EDIT, self::TASK_DELETE,self::TASK_TOGGLE])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) : bool
    {
        $user = $token->getUser();

        

        // On verifie si l'utilisateur est anonyme
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On verifie si l'utilisateur est Admin
        if($this->security->isGranted("ROLE_ADMIN")) {
            return true;
        }

        // On verifie si la tache a un proprietaire
        if($subject->getAuthor() === null){
            return false;
        }

        switch ($attribute) {
            case self::TASK_EDIT:

                //On verifie si l'utilisateur  peut editer 
                return $this->canEditTask($subject, $user);
                
                break;
            case self::TASK_DELETE:

                 //On verifie si l'utilisateur peut supprimer 
                 return $this->canDeleteTask($subject, $user);
                break;
            case self::TASK_TOGGLE:

                //On verifie si l'utilisateur peut marquer la tache 
                return $this->canToggleTask($subject, $user);
                break;
        }

        return false;
    }

    private function canEditTask(Task $task, User $user)
    {
        // L'auteur de la tache peut modifier
        return $user === $task->getAuthor();
    }

    private function canDeleteTask(Task $task, User $user)
    {
        // L'auteur de la tache peut supprimer
        return $user === $task->getAuthor();
    }
    private function canToggleTask(Task $task, User $user)
    {
        // L'auteur peut marquer la tache
        return $user === $task->getAuthor();
    }
}