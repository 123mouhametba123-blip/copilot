<?php
declare(strict_types=1);
namespace App\Repository;
use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
interface ReservationRepositoryInterface
{
    public function all(?int $salleId = null): iterable;
    public function find(int $id): ?Reservation;
    public function hasConflict(int $salleId, \DateTimeImmutable $debut, \DateTimeImmutable $fin): bool;
    public function create(CreerReservationDTO $dto): Reservation;
    public function cancel(Reservation $reservation): Reservation;
}