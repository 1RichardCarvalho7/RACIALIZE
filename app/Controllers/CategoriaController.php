<?php

declare(strict_types=1);

namespace App\Controllers;

class CategoriaController
{
    /** Exibe a categoria "Entenda o racismo". */
    public function entenda(): void
    {
        require __DIR__ . '/../Views/categorias/entenda.php';
    }

    /** Exibe a categoria "Conheça seus direitos". */
    public function conheca(): void
    {
        require __DIR__ . '/../Views/categorias/conheca.php';
    }

    /** Exibe a categoria "Denuncie o racismo". */
    public function denuncie(): void
    {
        require __DIR__ . '/../Views/categorias/denuncie.php';
    }

    /** Exibe a categoria "Ações afirmativas e cotas". */
    public function acoes(): void
    {
        require __DIR__ . '/../Views/categorias/acoes.php';
    }
}