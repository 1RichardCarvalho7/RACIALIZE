<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Admin;
use App\Validators\AdminValidator;
use PDOException;

/** Cadastro público do primeiro administrador (administrador geral). */
class AdminController extends Controller
{
    /** Exibe o formulário de criação do primeiro administrador. */
    public function create(): void
    {
        if (Admin::hasAny()) {
            $this->renderLocked();
            return;
        }

        $this->renderForm([], []);
    }

    /** Recebe e valida os dados enviados pelo formulário. */
    public function store(): void
    {
        $this->requirePost();

        // O cadastro público é permitido somente para criar o primeiro administrador.
        if (Admin::hasAny()) {
            $this->renderLocked();
            return;
        }

        $input = [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'senha' => (string) ($_POST['senha'] ?? ''),
            'confirmar_senha' => (string) ($_POST['confirmar_senha'] ?? ''),
        ];

        $errors = (new AdminValidator())->validate($input);

        if (!isset($errors['nome']) && Admin::usernameExists($input['nome'])) {
            $errors['nome'] = 'Este nome de usuário já está em uso.';
        }

        if ($errors) {
            http_response_code(422);
            $this->renderForm($errors, $input);
            return;
        }

        try {
            Admin::create($input['nome'], $input['senha'], Admin::NIVEL_GERAL);
        } catch (PDOException $exception) {
            // Também trata uma tentativa simultânea de cadastrar o mesmo usuário.
            if ($exception->getCode() === '23000') {
                http_response_code(422);
                $errors['nome'] = 'Este nome de usuário já está em uso.';
                $this->renderForm($errors, $input);
                return;
            }

            throw $exception;
        }

        $this->redirect('auth/login');
    }

    public function created(): void
    {
        $this->render('admins/created', ['title' => 'Cadastro concluído'], 'main');
    }

    private function renderForm(array $errors, array $input): void
    {
        $this->render('admins/create', [
            'title' => 'Cadastro do administrador',
            'errors' => $errors,
            // Não devolve senhas para o formulário.
            'old' => isset($input['nome']) ? ['nome' => $input['nome']] : [],
        ], 'main');
    }

    private function renderLocked(): void
    {
        http_response_code(403);
        $this->render('admins/locked', ['title' => 'Cadastro indisponível'], 'main');
    }
}
