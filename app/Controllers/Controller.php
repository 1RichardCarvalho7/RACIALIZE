<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Auth;

abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'painel'): void
    {
        $viewsPath = __DIR__ . '/../Views/';

        $data['flash'] = Auth::pullFlash();
        $data['currentUser'] = Auth::user();
        $data['csrf'] = Auth::csrfToken();
        extract($data, EXTR_SKIP);

        ob_start();
        require $viewsPath . $view . '.php';
        $content = ob_get_clean();

        require $viewsPath . 'layouts/' . $layout . '.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    protected function abort(int $code, string $message): void
    {
        http_response_code($code);
        exit($message);
    }

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->abort(405, 'Método não permitido.');
        }

        if (!Auth::csrfValid()) {
            $this->abort(403, 'Token de segurança inválido. Atualize a página e tente novamente.');
        }
    }

    /** Exige login e devolve o administrador autenticado. */
    protected function requireLogin(): array
    {
        $user = Auth::user();

        if ($user === null) {
            $this->redirect('auth/login');
        }

        return $user;
    }

    /** Exige login + permissão para gerenciar administradores (níveis 1 e 2). */
    protected function requireAdminManager(): array
    {
        $user = $this->requireLogin();

        if (!Admin::canManageAdmins($user)) {
            $this->abort(403, 'Você não tem permissão para gerenciar administradores.');
        }

        return $user;
    }
}
