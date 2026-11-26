<?php

declare(strict_types=1);

namespace App\Controllers;

class PaginaController
{
    /** Exibe a página "Sobre o projeto" (estática). */
    public function sobre(): void
    {
        require __DIR__ . '/../Views/layouts/sobre.php';
    }
}