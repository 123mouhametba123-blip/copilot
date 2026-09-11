<?php
declare(strict_types=1);
namespace App\Validation;

use Respect\Validation\Validator as v;

final class SalleValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $rules = [
            'nom' => v::stringType()->notEmpty()->length(2, 100),
            'batiment' => v::stringType()->notEmpty()->length(2, 100),
            'capacite' => v::intType()->between(1, 1000),
            'type' => v::in(['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion']),
        ];
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            if (!$rule->validate($value)) $errors[$field] = 'Valeur invalide.';
        }
        return new ValidationResult($errors, $data);
    }
}