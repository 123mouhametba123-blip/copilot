<?php
declare(strict_types=1);
namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\DomainException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\View\View;
use DateTimeImmutable;

final class ReservationController
{
    public function __construct(private ReservationRepositoryInterface $reservations, private SalleRepositoryInterface $salles, private ReservationValidator $validator, private CreerReservationService $creer, private AnnulerReservationService $annuler, private View $view) {}
    public function index(): string
    {
        $salleId = filter_input(INPUT_GET, 'salle_id', FILTER_VALIDATE_INT) ?: null;
        return $this->view->render('reservation/index', ['reservations' => $this->reservations->all($salleId), 'salles' => $this->salles->all(), 'selectedSalle' => $salleId]);
    }
    public function show(int $id): string { $reservation = $this->reservations->find($id); if (!$reservation) return $this->view->render('error/404'); return $this->view->render('reservation/show', compact('reservation')); }
    public function create(): string { return $this->view->render('reservation/form', ['salles' => $this->salles->all(), 'errors' => [], 'data' => []]); }
    public function store(): string
    {
        $data = array_map('trim', $_POST);
        $result = $this->validator->validate($data);
        if (!$result->isValid()) return $this->view->render('reservation/form', ['salles' => $this->salles->all(), 'errors' => $result->errors(), 'data' => $data]);
        try {
            $this->creer->execute(new CreerReservationDTO((int) $data['salle_id'], $data['responsable'], $data['email'], $data['motif'], new DateTimeImmutable($data['date_debut']), new DateTimeImmutable($data['date_fin'])));
        } catch (DomainException $exception) { return $this->view->render('reservation/form', ['salles' => $this->salles->all(), 'errors' => ['global' => $exception->getMessage()], 'data' => $data]); }
        header('Location: /reservations'); return '';
    }
    public function cancel(int $id): string { $this->annuler->execute($id); header('Location: /reservations'); return ''; }
}