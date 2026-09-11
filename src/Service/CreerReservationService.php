<?php
declare(strict_types=1);
namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use DateTimeImmutable;

final class CreerReservationService
{
    public function __construct(private SalleRepositoryInterface $salles, private ReservationRepositoryInterface $reservations) {}
    public function execute(CreerReservationDTO $dto): Reservation
    {
        $salle = $this->salles->find($dto->salleId);
        if (!$salle || !$salle->active) throw new SalleIndisponibleException('Cette salle ne peut pas etre reservee.');
        $now = new DateTimeImmutable();
        $duration = $dto->dateFin->getTimestamp() - $dto->dateDebut->getTimestamp();
        if ($dto->dateDebut >= $dto->dateFin) throw new SalleIndisponibleException('La date de debut doit preceder la date de fin.');
        if ($duration > 4 * 3600) throw new SalleIndisponibleException('Une reservation ne peut pas depasser quatre heures.');
        if ($dto->dateDebut <= $now) throw new SalleIndisponibleException('La reservation doit commencer dans le futur.');
        if ($this->reservations->hasConflict($dto->salleId, $dto->dateDebut, $dto->dateFin)) throw new SalleIndisponibleException('La salle est indisponible pendant cette periode.');
        return $this->reservations->create($dto);
    }
}