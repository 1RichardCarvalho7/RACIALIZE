<?php

declare(strict_types=1);

namespace App\Validators;

class AdminValidator
{

    /**
     * $passwordRequired false na edição: senha vazia = manter a atual
     *
     * @param array $input
     * @param boolean $passwordRequired
     * @return array
     */
    public function validate(array $input, bool $passwordRequired = true): array
    {
        $errors = [];

        $name = trim((string) ($input['nome'] ?? ''));
        $password = (string) ($input['senha'] ?? '');
        $passwordConfirmation = (string) ($input['confirmar_senha'] ?? '');

        if ($name === '') {
            $errors['nome'] = 'Informe um nome de usuário.';
        } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/D', $name)) {
            $errors['nome'] = 'Use de 3 a 30 caracteres: letras, números, ponto, hífen ou sublinhado.';
        }

        if ($passwordRequired || $password !== '' || $passwordConfirmation !== '') {
            if (strlen($password) < 8) {
                $errors['senha'] = 'A senha deve possuir pelo menos 8 caracteres.';
            }

            if ($passwordConfirmation === '') {
                $errors['confirmar_senha'] = 'Confirme a senha.';
            } elseif ($password !== $passwordConfirmation) {
                $errors['confirmar_senha'] = 'As senhas não coincidem.';
            }
        }

        return $errors;
    }
}
