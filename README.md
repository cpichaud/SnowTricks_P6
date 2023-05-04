# SnowTricks_P6 - Développez de A à Z le site communautaire SnowTricks

## Contexte 
Ce projet a pour but de créer un site communautaire permettant aux passionnés de snowboard d'apprendre les figures de ce sport. Le site permettra aux utilisateurs de consulter une liste de figures, de créer de nouvelles figures, de modifier des figures existantes et de discuter de chaque figure dans un espace de discussion dédié.

## Prérequis

* PHP 8 ou supérieur
* Symfony 5.4
* Composer
* MySQL

## Installation

1- Clonez ce repository sur votre ordinateur
2- Installez les dépendances en exécutant la commande suivante à la racine du projet :

```composer install```

Effectuer un migration de donnée :

```php bin/console doctrine:migrations:migrate```

Télécharger les fixtures

```php bin/console doctrine:fixtures:load```

Lancez le serveur en exécutant la commande suivante :

```symfony server:start```



## Utilisation
Le site permet aux utilisateurs de consulter une liste de figures sur la page d'accueil. Ils peuvent également créer de nouvelles figures en cliquant sur le bouton "Créer une figure" et modifier des figures existantes en cliquant sur le bouton "Modifier" sur la page de présentation de chaque figure. Les utilisateurs peuvent également discuter de chaque figure dans l'espace de discussion dédié.

