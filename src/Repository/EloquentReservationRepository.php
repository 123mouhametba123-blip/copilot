<?php
declare(strict_types=1);
namespace App\Repository;
use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use DateTimeImmutable;
final class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function all(?int $salleId = null): iterable
    { $query = Reservation::with('salle')->latest('date_debut'); return $query->when($salleId, fn ($q) => $q->where('salle_id', $salleId))->get(); }
    public function find(int $id): ?Reservation { return Reservation::with('salle')->find($id); }
    public function hasConflict(int $salleId, DateTimeImmutable $debut, DateTimeImmutable $fin): bool
    { return Reservation::where('salle_id', $salleId)->where('statut', 'confirmee')->where('date_debut', '<', $fin)->where('date_fin', '>', $debut)->exists(); }
    public function create(CreerReservationDTO $dto): Reservation
    { return Reservation::create(['salle_id' => $dto->salleId, 'responsable' => $dto->responsable, 'email' => $dto->email, 'motif' => $dto->motif, 'date_debut' => $dto->dateDebut, 'date_fin' => $dto->dateFin, 'statut' => 'confirmee']); }
    public function cancel(Reservation $reservation): Reservation { $reservation->statut = 'annulee'; $reservation->save(); return $reservation; }
}