<?php
declare(strict_types=1);
namespace Tests\Unit;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\CreerReservationService;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CreerReservationServiceTest extends TestCase
{
    public function testUneReservationVoisineEstAcceptee(): void
    {
        $salle = new Salle(['active' => true]);
        $salles = new class($salle) implements SalleRepositoryInterface {
            public function __construct(private Salle $salle) {}
            public function all(): iterable { return [$this->salle]; }
            public function find(int $id): ?Salle { return $this->salle; }
            public function save(Salle $salle): Salle { return $salle; }
        };
        $reservations = new class implements ReservationRepositoryInterface {
            public function all(?int $salleId = null): iterable { return []; }
            public function find(int $id): ?Reservation { return null; }
            public function hasConflict(int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin): bool { return false; }
            public function create(CreerReservationDTO $dto): Reservation { return new Reservation(); }
            public function cancel(Reservation $reservation): Reservation { return $reservation; }
        };
        $service = new CreerReservationService($salles, $reservations);
        $result = $service->execute(new CreerReservationDTO(1, 'Awa Ndiaye', 'awa@example.com', 'Cours PHP', new DateTimeImmutable('+1 day 10:00'), new DateTimeImmutable('+1 day 12:00')));
        self::assertInstanceOf(Reservation::class, $result);
    }

    public function testUnConflitEstRefuse(): void
    {
        $salle = new Salle(['active' => true]);
        $salles = $this->createStub(SalleRepositoryInterface::class);
        $salles->method('find')->willReturn($salle);
        $reservations = $this->createStub(ReservationRepositoryInterface::class);
        $reservations->method('hasConflict')->willReturn(true);
        $service = new CreerReservationService($salles, $reservations);
        $this->expectException(SalleIndisponibleException::class);
        $service->execute(new CreerReservationDTO(1, 'Awa Ndiaye', 'awa@example.com', 'Cours PHP', new DateTimeImmutable('+1 day 10:00'), new DateTimeImmutable('+1 day 12:00')));
    }
}