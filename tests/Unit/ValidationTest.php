<?php
declare(strict_types=1);
namespace Tests\Unit;

use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase
{
    public function testUneReservationAvecEmailInvalideEstRefusee(): void
    {
        $result = (new ReservationValidator())->validate($this->reservationData(['email' => 'adresse-invalide']));
        self::assertArrayHasKey('email', $result->errors());
    }

    public function testLesChampsInvalidesDuneSalleSontSignales(): void
    {
        $result = (new SalleValidator())->validate(['nom' => '', 'batiment' => 'B', 'capacite' => -1, 'type' => 'inconnu']);
        self::assertFalse($result->isValid());
        self::assertArrayHasKey('nom', $result->errors());
        self::assertArrayHasKey('capacite', $result->errors());
        self::assertArrayHasKey('type', $result->errors());
    }

    public function testUneDateIncorrecteEstRefusee(): void
    {
        $result = (new ReservationValidator())->validate($this->reservationData(['date_debut' => 'demain']));
        self::assertArrayHasKey('date_debut', $result->errors());
    }

    private function reservationData(array $overrides = []): array
    {
        return array_replace([
            'salle_id' => 1,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa@example.com',
            'motif' => 'Cours PHP',
            'date_debut' => '2026-09-12T10:00',
            'date_fin' => '2026-09-12T12:00',
        ], $overrides);
    }
}