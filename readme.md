## Table des matières
***
1. [Description](#1--description)
2. [Version](#2--version)
3. [Création du projet](#3--cration-du-projet)
4. [Installation Doctrine](#4--installation-doctrine)
5. [Configuration de la base de données](#5--configuration-base-de-donnes)
6. [Création de la base de données](#6--cration-base-de-donnes)
7. [Création d'entités](#7--cration-dentit)
8. [Migrations et mise à jour de la BDD](#8--migrations-et-mise--jour-de-la-bdd)
9. [Création controller](#9--cration-controller)
10. [Execution du projet](#10--excution-du-projet)

### 1- Description
***
Interface utilisateur connectée à l'API de BTS_RAMINAGROBIS_API.  
[API du projet lié par l'API C#](#https://github.com/Cahuete0512/bts_RAMINAGROBIS_API.git)  
Permet aux fournisseurs de proposer un meilleur prix de produit :
+ Si la pastille est verte, il a le meilleur tarif
+ Si la pastille est orange, il est à égalité avec le prix d'un produit d'un autre fournisseur
+ Si la pastille est rouge, il n'a pas le meilleur prix. Il peut donc corriger le prix du produit pour obtenir une pastille verte.

### 2- Version
***
+ PHP : version 8.0.11
+ npm : version 8.5.1
+ Symfony CLI : version 5.3.4

### 3- Création du projet:
***
composer create-project symfony/website-skeleton nom_du_projet

### 4- Installation doctrine:  
***
bibliothèque fournissant de puissants outils rendant les interactions avec les bases de données simples et flexibles.

    a- composer require symfony/orm-pack 

    b- composer require symfony/maker-bundle --dev

### 5- Configuration base de données:
***
    Les paramètres de la connexion à la base de données sont stockés dans la variable DATABASE_URL qui existe dans le fichier .env.
    Exemple:
    DATABASE_URL=‘mysql://db_user:db_password@127.0.0.1:3306/db_name’
    db_user: root
    db_password: par défaut vide 
    db_name: nom de votre base par exemple 'crud_symfony'
DATABASE_URL='mysql://root:@127.0.0.1:3306/crud_symfony'

### 6- Création base de données 
***
$ php bin/console doctrine:database:create

### 7- Création d’entité:
***
$ php bin/console make:entity
>nom de classe/entite

### 8- Migrations et mise à jour de la BDD   
***
Création des tables / schémas de la base de données

    a- $ php bin/console make:migration 
    b- $ php bin/console doctrine:migrations:migrate
    c- $ php bin/console doctrine:schema:update

### 9- Création controller
***
$ php console make:controller
>nom de la classe

### 10- Exécution du projet
***  
>Se placer dans le projet :  

    $ cd bts_RAMINAGROBIS_Symfony  

>Pour start le server :  

    $ Symfony server:start  

    