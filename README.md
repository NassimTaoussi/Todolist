# Projet Todolist de Todo&Co

## Installation

1. Cliquer sur le bouton "Clone" et copier le lien du repository en SSH ou HTTPS selon votre souhait.

2. Cloner le projet dans un dossier local avec la commande 
`git clone liendurepository`

3. A la racine du projet installer les bibliothèques avec la commande :
`symfony composer install` 

4. Modifier les variables d'environement dans le fichier .env en renseignant le nom de votre base de données avec son nom et les informations de connexion.
Puis utliser la commande 
`php bin/console doctrine:database:create`

5. Pour installer les tables de données via le système de migrations: 
`php bin/console doctrine:migrations:migrate`

6. Installer un jeu de données: 
`php bin/console doctrine:fixtures:load`

7. Démarrer le serveur de Symfony: 
`symfony server:start`

8. Puis démarré webpack avec la commande 
`npm run watch`

9. En cas de bugs éventuel, vider le cache avec la commande 
`php bin/console cache:clear`