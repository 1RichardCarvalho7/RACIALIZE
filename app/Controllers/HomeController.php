<?php

declare(strict_types=1);

namespace App\Controllers;

class HomeController
{
    /** Exibe a tela inicial do site. */
    public function index(): void
    {
        require __DIR__ . '/../Views/layouts/inicial.php';
    }
}
