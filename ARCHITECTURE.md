# Architecture

- **MVC / Front Controller** : `public/index.php` demarre l'application ; les controleurs coordonnent les cas d'usage et les templates affichent les donnees.
- **Router** : `routes/web.php` declare les routes ; `Application` gere 404, 405 et les handlers.
- **Validator / DTO** : les validateurs Respect controlent la forme HTTP avant conversion en `CreerReservationDTO`.
- **ORM / Active Record** : `Salle` et `Reservation` encapsulent tables, casts et relations Eloquent.
- **Repository** : les interfaces et implementations Eloquent empechent les controleurs de contenir des requetes.
- **Service** : `CreerReservationService` porte les regles de date, duree, salle active et chevauchement.
- **Injection / IoC** : les dependances sont recues par constructeur ; PHP-DI relie les interfaces a leurs implementations.

Cette organisation applique notamment la responsabilite unique, l'ouverture/fermeture via les interfaces, la substitution des implementations et l'inversion des dependances. La limite principale est le nombre de classes, acceptable ici car l'exercice vise explicitement ces frontieres.