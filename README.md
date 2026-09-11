# Campus Rooms

Application PHP orientee objet de gestion des salles et reservations universitaires.

## Prerequis

- Docker et Docker Compose
- ou PHP 8.2+, MySQL 8+ et Composer 2

## Demarrage Docker

```bash
cp .env.example .env
docker compose up --build
```

L'application est disponible sur http://localhost:8000. Le conteneur PHP utilise le serveur integre PHP, sans Apache. MySQL reste accessible uniquement sur le reseau Docker.

## Commandes locales

```bash
composer install
cp .env.example .env
php database/migrate.php
php database/seed.php
composer serve
```

Les repositories isolent Eloquent, les services portent les regles de disponibilite et PHP-DI construit les dependances. Les dates voisines sont permises ; un conflit est detecte par `nouveauDebut < finExistante` et `nouvelleFin > debutExistant`.

## Tests

```bash
composer test
```

## Architecture

Le Front Controller est `public/index.php`. FastRoute distribue les requetes, les controleurs orchestrent validation et services, et les vues n'accedent jamais directement a la base. Voir `ARCHITECTURE.md` pour le detail des choix.