<?php
declare(strict_types=1);
namespace App\Service;
use App\Exception\ReservationIntrouvableException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
final class AnnulerReservationService
{
    public function __construct(private ReservationRepositoryInterface $reservations) {}
    public function execute(int $id): Reservation
    {
        $reservation = $this->reservations->find($id);
        if (!$reservation) throw new ReservationIntrouvableException('Reservation introuvable.');
        return $this->reservations->cancel($reservation);
    }
}