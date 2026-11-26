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

        $input = [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'senha' => (string) ($_POST['senha'] ?? ''),
            'confirmar_senha' => (string) ($_POST['confirmar_senha'] ?? ''),
        ];

        $errors = (new AdminValidator())->validate($input);

        if ($errors) {
            http_response_code(422);
            $this->renderForm($errors, $input);
            return;
        }

        try {
            // Confere novamente se o primeiro acesso continua liberado.
            if (Admin::hasAny()) {
                $this->renderLocked();
                return;
            }

            if (Admin::usernameExists($input['nome'])) {
                http_response_code(422);
                $errors['nome'] = 'Este nome de usuário já está em uso.';
                $this->renderForm($errors, $input);
                return;
            }

            // createFirst repete a verificação dentro de um bloqueio do MySQL.
            if (!Admin::createFirst($input['nome'], $input['senha'])) {
                if (Admin::hasAny()) {
                    $this->renderLocked();
                    return;
                }

                http_response_code(503);
                $errors['geral'] = 'Outro cadastro inicial pode estar em andamento. Atualize a página e tente novamente.';
                $this->renderForm($errors, $input);
                return;
            }
        } catch (PDOException $exception) {
            // O detalhe técnico fica no log do Apache/PHP, não exposto ao visitante.
            error_log('Falha no cadastro do primeiro administrador: ' . $exception->getMessage());

            if ($exception->getCode() === '23000') {
                http_response_code(422);
                $errors['nome'] = 'Este nome de usuário já está em uso.';
            } else {
                http_response_code(500);
                $errors['geral'] = 'Não foi possível salvar o administrador. Verifique se o MySQL está ativo e se a porta e o banco em config/database.php estão corretos.';
            }

            $this->renderForm($errors, $input);
            return;
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
            'old' => isset($input['nome']) ? ['nome' => $input['nome']] : [],
        ], 'minimal');
    }

    private function renderLocked(): void
    {
        http_response_code(403);
        $this->render('admins/locked', ['title' => 'Cadastro indisponível'], 'main');
    }
}
