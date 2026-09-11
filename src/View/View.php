<?php
declare(strict_types=1);
namespace App\View;
final class View
{
    public function render(string $template, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require dirname(__DIR__, 2) . '/templates/' . $template . '.php';
        return (string) ob_get_clean();
    }
}