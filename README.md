# symfonyEcfBiblio

Ce repo contient une application de gestion de bibliothèque.

## Prérequis

- Linux, MacOS ou Windows
- Bash
- PHP 8
- composer
- symfony-cli
- MariaDB 10
- docker (optionnel)

## Intallation

```
git clone https://github.com/EmmaCremeaux/symfonyEcfBiblio
cd symfony
composer install
```

Créez une base de données et un utilisateur dédié pour cette base de données.

## Configuration

Créez un fichier `.env.local` à la racine du projet :

```
APP_ENV=dev
APP_Debug=true
APP_SECRET=465759d33b218807fb0d005fe45b892e
DATABASE_URL="mysql://symfonyEcfBiblio:123@127.0.0.1:3306/symfonyEcfBiblio?serverVersion=mariadb-10.6.12&charset=utf8mb4"
```

Pensez à changer la variable `APP_SECRET` et les code d'accès `123` dans la variable `DATABASE_URL`.

**ATTENTION : `APP_SECRET` doit être une chaîne de caractère de 32 caractère en hexadécimal.**

## Migration et fixtures

pour que l'application soit utilisable, vous devez créer le schéma de base de données et charger les données :

```
bin/dofilo.sh
```

## Utilisation

Lancez le serveur web de développement : 

```
symfony serve
```

puis ouvrez la page suivante : [home page](https://localhost:8000)
pour vérifier les données de test : 
                [page emprunteur](https://localhost:8000/test/emprunteur)
                [page emprunt](https://localhost:8000/test/emprunt)
                [page livre](https://localhost:8000/test/livre)
                [page user](https://localhost:8000/test/user)

## Mentions Légales

Ce projet est sous licence MIT.

la licence est disponible ici : [MIT LICENCE](LICENCE).
