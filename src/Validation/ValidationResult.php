<?php
declare(strict_types=1);
namespace App\Validation;

final readonly class ValidationResult
{
    public function __construct(private array $errors, private array $data) {}
    public function isValid(): bool { return $this->errors === []; }
    public function errors(): array { return $this->errors; }
    public function data(): array { return $this->data; }
}