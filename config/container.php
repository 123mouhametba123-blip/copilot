<?php
declare(strict_types=1);
use App\Repository\SalleRepositoryInterface;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\EloquentReservationRepository;
use function DI\autowire;
return [
    SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
    ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),
];