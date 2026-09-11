<?php
declare(strict_types=1);
namespace App\Validation;

use Respect\Validation\Validator as v;

final class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $rules = [
            'salle_id' => v::intType()->between(1, PHP_INT_MAX),
            'responsable' => v::stringType()->notEmpty()->length(2, 120),
            'email' => v::email(),
            'motif' => v::stringType()->notEmpty()->length(5, 255),
            'date_debut' => v::dateTime('Y-m-d\\TH:i'),
            'date_fin' => v::dateTime('Y-m-d\\TH:i'),
        ];
        $errors = [];
        foreach ($rules as $field => $rule) {
            if (!$rule->validate($data[$field] ?? null)) $errors[$field] = 'Valeur invalide.';
        }
        return new ValidationResult($errors, $data);
    }
}