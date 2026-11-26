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

    /** Exibe a página "Fontes e referências" (estática). */
    public function referencias(): void
    {
        require __DIR__ . '/../Views/layouts/referencias.php';
    }
}