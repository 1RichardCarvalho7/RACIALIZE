<?php

declare(strict_types=1);

namespace App\Models;

class Admin
{
    public static function hasAny(): bool
    {
        $stmt = Database::connect()->query(
            'SELECT 1 FROM administrador LIMIT 1'
        );

        return (bool) $stmt->fetchColumn();
    }

    public static function usernameExists(string $name): bool
    {
        $stmt = Database::connect()->prepare(
            'SELECT 1 FROM administrador WHERE nome = :nome LIMIT 1'
        );
        $stmt->execute(['nome' => $name]);

        return (bool) $stmt->fetchColumn();
    }

    public static function create(
        string $name,
        string $password
    ): int {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = Database::connect()->prepare(
            'INSERT INTO administrador (nome, senhaHash)
             VALUES (:nome, :senhaHash)'
        );

        $stmt->execute([
            'nome' => $name,
            'senhaHash' => $passwordHash,
        ]);

        return (int) Database::connect()->lastInsertId();
    }
}
