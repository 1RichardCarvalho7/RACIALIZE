<?php

declare(strict_types=1);

namespace App\Controllers;

class PainelController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $this->render('painel/index', ['title' => 'Painel administrativo']);
    }
}
