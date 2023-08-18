# Contribution au projet "Todolist"

[Lien du repository Gitlab du projet](https://gitlab.com/NassimTaoussi/todolist)

## Introduction

Pour contribuer au projet, que ce soit pour améliorer les fonctionnalités déjà existantes ou encore en ajouter de nouvelles, ou corriger des bugs, veuillez procéder comme indiqué ci-dessous :

Avant d'entamer un quelconque développement, il faudra réaliser les diagrammes de sequences et de cas d'utilisation
Si vos modifications impactent la base de données il faudra modifier les diagrammes de classe et le modèle physique de données. Ces diagrammes devront être intégré à la demande de merge request.

## Installation

1. En premier lieu il faudra de connecter sur Gitlab, ou s'inscrire si vous n'avez pas de compte
2. Ensuite récupérer le projet distant [ici](https://gitlab.com/NassimTaoussi/todolist)
3. Cliquer sur le bouton "Clone" et copier le lien du repository en SSH ou HTTPS selon votre souhait.
4. Cloner le projet dans un dossier local avec la commande git clone liendurepository.
5. A la racine du projet installer les bibliothèques avec la commande :
`symfony composer install` 
6. Modifier les variables d'environement dans le fichier .env
en renseignant le nom de votre base de données avec son nom et les informations de connexion.
Puis utliser la commande `php bin/console doctrine:database:create`
7. Pour installer les tables de données via le système de migrations: `php bin/console doctrine:migrations:migrate`
8. Installer un jeu de données: `php bin/console doctrine:fixtures:load`
9. Démarrer le serveur de Symfony: `symfony server:start`
10. Puis démarré webpack avec la commande `npm run watch`
11. En cas de bugs éventuel, vider le cache avec la commande `php bin/console cache:clear`

## Développement

1. Quelle que soit la modification que vous envisagez, il faudra au préalable une branche spécifique portant un nom explicite sur ce quoi vous avez prévu d'intervenir. Pour créer une branche, utiliser la commande: `git checkout -b nomdelabranche`.
2. L'entreprise Todolist&Co travaille en TDD (Test Driven Development), il faut donc tout d'abord implémenter les tests avant d'entamer un quelconque développement d'une fonctionnalité.
3. Vérifier l’ensemble des tests et s'assurer d'une couverture de code d'au minimum 70%.
4. Pour entamer le développement des modfications il faudra :
    - Utiliser des commentaires sur chaque classe, chaque méthode et également où celà semble utile.
    - Respecter les coding standards de Symfony [documentation à ce sujet ici](https://symfony.com/doc/4.4/contributing/code/standards.html).
5. Faire des commits pertinents en suivant la convention de nommage angular [ici](https://www.conventionalcommits.org/en/v1.0.0-beta.4/) exemple : 
`git commit -m "<type>[optional scope]: <description>`.
6. Pour chaque commit, il faudra vérifier la qualité du code ainsi que la performance
    - Utiliser Codacy pour tester la qualité du code et obtenir une note de B au minimum pour votre branche. Modifier le code si besoin.
    - Utiliser le profiler de symfony pour les tests de performance
        - Comparer le différenciel de performance entre rapport l'état initial de l'application et les nouveaux developpements.
        - Si la performance s'en trouve quelque peu dégradée, trouver des solutions d'optimisation car ces dernières ne pouront être intégré en production.

## Envoi des modifications apporté au projet

1. Envoyer la branche sur Gitlab avec la commande `git push origin nomdelabranche`.
2. Se rendre sur la page du repository.
3. Une notification indique la réception de la branche, cliquer sur le bouton "Compare & merge" pour créer une merge request.
4. Complètez le formulaire en expliquant succinctement les modifications effectuées.
5. Valider la merge request.
6. Répondre aux éventuelles questions si besoin de la part de l'équipe de dev.
7. En fonction de la pertinence des modifications, celles-ci seront intégrées on non au projet.