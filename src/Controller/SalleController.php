<?php
declare(strict_types=1);
namespace App\Controller;

use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\View\View;

final class SalleController
{
    public function __construct(private SalleRepositoryInterface $salles, private SalleValidator $validator, private View $view) {}
    public function index(): string { return $this->view->render('salle/index', ['salles' => $this->salles->all()]); }
    public function show(int $id): string { $salle = $this->salles->find($id); if (!$salle) return $this->view->render('error/404'); return $this->view->render('salle/show', compact('salle')); }
    public function create(): string { return $this->view->render('salle/form', ['salle' => null, 'errors' => []]); }
    public function edit(int $id): string
    {
        $salle = $this->salles->find($id);
        if (!$salle) return $this->view->render('error/404');
        return $this->view->render('salle/form', ['salle' => $salle, 'errors' => [], 'editing' => true]);
    }
    public function store(): string
    {
        $data = ['nom' => trim((string) ($_POST['nom'] ?? '')), 'batiment' => trim((string) ($_POST['batiment'] ?? '')), 'capacite' => (int) ($_POST['capacite'] ?? 0), 'type' => (string) ($_POST['type'] ?? '')];
        $result = $this->validator->validate($data);
        if (!$result->isValid()) return $this->view->render('salle/form', ['salle' => (object) $data, 'errors' => $result->errors()]);
        $this->salles->save(new Salle($data + ['active' => true]));
        header('Location: /salles'); return '';
    }
    public function update(int $id): string
    {
        $salle = $this->salles->find($id);
        if (!$salle) return $this->view->render('error/404');
        $data = ['nom' => trim((string) ($_POST['nom'] ?? '')), 'batiment' => trim((string) ($_POST['batiment'] ?? '')), 'capacite' => (int) ($_POST['capacite'] ?? 0), 'type' => (string) ($_POST['type'] ?? '')];
        $result = $this->validator->validate($data);
        if (!$result->isValid()) return $this->view->render('salle/form', ['salle' => (object) ($data + ['active' => $salle->active]), 'errors' => $result->errors(), 'editing' => true]);
        $salle->fill($data);
        $this->salles->save($salle);
        header('Location: /salles/' . $id);
        return '';
    }
    public function toggle(int $id): string
    {
        $salle = $this->salles->find($id);
        if (!$salle) return $this->view->render('error/404');
        $salle->active = !$salle->active;
        $this->salles->save($salle);
        header('Location: /salles/' . $id);
        return '';
    }
}