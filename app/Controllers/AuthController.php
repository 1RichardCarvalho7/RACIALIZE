<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Auth;

class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::user() !== null) {
            $this->redirect('painel/index');
        }

        $this->render('auth/login', [
            'title' => 'Entrar',
            'error' => null,
            'old' => '',
            'firstAccess' => !Admin::hasAny(),
        ], 'main');
    }

    public function authenticate(): void
    {
        $this->requirePost();

        $name = trim((string) ($_POST['nome'] ?? ''));
        $password = (string) ($_POST['senha'] ?? '');

        $admin = Admin::findForLogin($name);

        // Sem usuário, verifica contra um hash qualquer para não revelar (pelo tempo) se o nome existe.
        $hash = $admin['senhaHash'] ?? password_hash('invalido', PASSWORD_DEFAULT);

        if ($admin === null || !password_verify($password, $hash)) {
            http_response_code(401);
            $this->render('auth/login', [
                'title' => 'Entrar',
                'error' => 'Usuário ou senha inválidos.',
                'old' => $name,
                'firstAccess' => false,
            ], 'main');
            return;
        }

        Auth::login($admin);
        $this->redirect('painel/index');
    }

    public function logout(): void
    {
        $this->requirePost();
        Auth::logout();
        $this->redirect('home/index');
    }
}
