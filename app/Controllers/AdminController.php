<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Admin;
use App\Validators\AdminValidator;
use PDOException;

class AdminController
{
    /** Exibe o formulário de criação do primeiro administrador. */
    public function create(): void
    {
        if (Admin::hasAny()) {
            http_response_code(403);
            $this->render('admins/locked', [
                'title' => 'Cadastro indisponível',
            ]);
            return;
        }

        $this->render('admins/create', [
            'title' => 'Cadastro do administrador',
            'errors' => [],
            'old' => [],
            'csrf' => $this->csrfToken(),
        ]);
    }

    /** Recebe e valida os dados enviados pelo formulário. */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido.');
        }

        $this->checkCsrf();

        // O cadastro público é permitido somente para criar o primeiro administrador.
        if (Admin::hasAny()) {
            http_response_code(403);
            $this->render('admins/locked', [
                'title' => 'Cadastro indisponível',
            ]);
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
            $this->renderFormWithErrors($errors, $input);
            return;
        }

        try {
            Admin::create($input['nome'], $input['senha']);
        } catch (PDOException $exception) {
            // Também trata uma tentativa simultânea de cadastrar o mesmo usuário.
            if ($exception->getCode() === '23000') {
                http_response_code(422);
                $errors['nome'] = 'Este nome de usuário já está em uso.';
                $this->renderFormWithErrors($errors, $input);
                return;
            }

            throw $exception;
        }

        header('Location: ' . url('admin/created'));
        exit;
    }

    public function created(): void
    {
        $this->render('admins/created', [
            'title' => 'Cadastro concluído',
        ]);
    }

    private function renderFormWithErrors(array $errors, array $input): void
    {
        $this->render('admins/create', [
            'title' => 'Cadastro do administrador',
            'errors' => $errors,
            // Não devolve senhas para o formulário após uma validação malsucedida.
            'old' => [
                'nome' => $input['nome']
            ],
            'csrf' => $this->csrfToken(),
        ]);
    }

    private function csrfToken(): string
    {
        $this->startSession();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    private function checkCsrf(): void
    {
        $this->startSession();

        $sessionToken = $_SESSION['csrf_token'] ?? '';
        $formToken = $_POST['csrf_token'] ?? '';

        if (
            !is_string($sessionToken)
            || $sessionToken === ''
            || !is_string($formToken)
            || !hash_equals($sessionToken, $formToken)
        ) {
            http_response_code(403);
            exit('Token de segurança inválido. Atualize a página e tente novamente.');
        }
    }

    private function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    private function render(string $view, array $data = []): void
    {
        $viewsPath = __DIR__ . '/../Views/';

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewsPath . $view . '.php';
        $content = ob_get_clean();

        require $viewsPath . 'layouts/main.php';
    }
}
