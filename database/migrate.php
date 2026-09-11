<?php
declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;

/** @var Manager $capsule */
$capsule = require dirname(__DIR__) . '/config/database.php';
$schema = $capsule->schema();

if (!$schema->hasTable('salles')) {
    $schema->create('salles', function ($table): void {
        $table->id();
        $table->string('nom', 100);
        $table->string('batiment', 100);
        $table->unsignedInteger('capacite');
        $table->string('type', 30);
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
}
if (!$schema->hasTable('reservations')) {
    $schema->create('reservations', function ($table): void {
        $table->id();
        $table->foreignId('salle_id')->constrained('salles');
        $table->string('responsable', 120);
        $table->string('email', 255);
        $table->string('motif', 255);
        $table->dateTime('date_debut');
        $table->dateTime('date_fin');
        $table->string('statut', 20)->default('confirmee');
        $table->timestamps();
        $table->index(['salle_id', 'statut', 'date_debut', 'date_fin']);
    });
}
echo "Migration terminee.\n";