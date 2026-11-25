<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Auth;
use App\Validators\AdminValidator;
use PDOException;

class AdministradoresController extends Controller
{
    public function index(): void
    {
        $this->requireAdminManager();

        $this->render('administradores/index', [
            'title' => 'Administradores',
            'admins' => Admin::all(),
        ]);
    }

    public function create(): void
    {
        $this->requireAdminManager();

        $this->renderForm(null, ['nome' => '', 'nivel' => Admin::NIVEL_EDITOR], []);
    }

    public function store(): void
    {
        $this->requireAdminManager();
        $this->requirePost();

        $input = $this->readInput();
        $errors = $this->validateInput($input, true, null);

        if ($errors) {
            http_response_code(422);
            $this->renderForm(null, $input, $errors);
            return;
        }

        try {
            Admin::create($input['nome'], $input['senha'], $input['nivel']);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                http_response_code(422);
                $this->renderForm(null, $input, ['nome' => 'Este nome de usuário já está em uso.']);
                return;
            }
            throw $e;
        }

        Auth::flash('success', 'Administrador "' . $input['nome'] . '" criado com sucesso.');
        $this->redirect('administradores/index');
    }

    public function edit(string $id = ''): void
    {
        $actor = $this->requireAdminManager();
        $target = $this->findManageable($actor, $id);

        $this->renderForm($target, [
            'nome' => $target['nome'],
            'nivel' => (int) $target['nivelAdministrador'],
        ], []);
    }

    public function update(string $id = ''): void
    {
        $actor = $this->requireAdminManager();
        $this->requirePost();
        $target = $this->findManageable($actor, $id);
        $targetId = (int) $target['id_administrador'];

        $input = $this->readInput();
        $errors = $this->validateInput($input, false, $targetId);

        if ($errors) {
            http_response_code(422);
            $this->renderForm($target, $input, $errors);
            return;
        }

        try {
            Admin::update($targetId, $input['nome'], $input['nivel'], $input['senha']);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                http_response_code(422);
                $this->renderForm($target, $input, ['nome' => 'Este nome de usuário já está em uso.']);
                return;
            }
            throw $e;
        }

        Auth::flash('success', 'Administrador "' . $input['nome'] . '" atualizado.');
        $this->redirect('administradores/index');
    }

    public function destroy(string $id = ''): void
    {
        $actor = $this->requireAdminManager();
        $this->requirePost();
        $target = $this->findManageable($actor, $id);

        try {
            Admin::delete((int) $target['id_administrador']);
        } catch (PDOException $e) {
            // Chave estrangeira: artigos, vídeos ou gráficos ainda pertencem a este administrador.
            if ($e->getCode() === '23000') {
                Auth::flash(
                    'danger',
                    'Não foi possível excluir "' . $target['nome'] . '": ele possui conteúdos vinculados. '
                    . 'Transfira ou exclua esses conteúdos primeiro.'
                );
                $this->redirect('administradores/index');
            }
            throw $e;
        }

        Auth::flash('success', 'Administrador "' . $target['nome'] . '" excluído.');
        $this->redirect('administradores/index');
    }

    // ----- Auxiliares -----

    /** Carrega o alvo e garante que o usuário logado pode gerenciá-lo. */
    private function findManageable(array $actor, string $id): array
    {
        $target = ctype_digit($id) ? Admin::find((int) $id) : null;

        if ($target === null) {
            $this->abort(404, 'Administrador não encontrado.');
        }

        if (!Admin::canManage($actor, $target)) {
            $this->abort(403, 'Você não pode alterar este administrador.');
        }

        return $target;
    }

    private function readInput(): array
    {
        return [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'senha' => (string) ($_POST['senha'] ?? ''),
            'confirmar_senha' => (string) ($_POST['confirmar_senha'] ?? ''),
            'nivel' => (int) ($_POST['nivel'] ?? 0),
        ];
    }

    private function validateInput(array $input, bool $passwordRequired, ?int $exceptId): array
    {
        $errors = (new AdminValidator())->validate($input, $passwordRequired);

        if (!isset($errors['nome']) && Admin::usernameExists($input['nome'], $exceptId)) {
            $errors['nome'] = 'Este nome de usuário já está em uso.';
        }

        // O nível 1 nunca pode ser atribuído a ninguém.
        if (!in_array($input['nivel'], Admin::ASSIGNABLE_LEVELS, true)) {
            $errors['nivel'] = 'Escolha um nível de permissão válido.';
        }

        return $errors;
    }

    private function renderForm(?array $target, array $input, array $errors): void
    {
        $this->render('administradores/form', [
            'title' => $target ? 'Editar administrador' : 'Novo administrador',
            'target' => $target,
            // Senhas nunca voltam para o formulário.
            'old' => ['nome' => $input['nome'], 'nivel' => (int) $input['nivel']],
            'errors' => $errors,
        ]);
    }
}
