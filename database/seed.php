<?php
declare(strict_types=1);

use App\Model\Salle;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config/database.php';

$salles = [
    ['nom' => 'Amphitheatre A', 'batiment' => 'Batiment A', 'capacite' => 250, 'type' => 'amphitheatre'],
    ['nom' => 'Salle B12', 'batiment' => 'Batiment B', 'capacite' => 40, 'type' => 'cours'],
    ['nom' => 'Laboratoire Chimie', 'batiment' => 'Batiment C', 'capacite' => 24, 'type' => 'laboratoire'],
    ['nom' => 'Salle Informatique 1', 'batiment' => 'Batiment D', 'capacite' => 30, 'type' => 'informatique'],
    ['nom' => 'Salle de reunion', 'batiment' => 'Batiment administratif', 'capacite' => 12, 'type' => 'reunion'],
];
foreach ($salles as $data) {
    Salle::firstOrCreate(['nom' => $data['nom']], $data);
}
echo "Donnees initiales ajoutees.\n";