<?php

declare(strict_types=1);

namespace App\Models;

class Admin
{
        /** Primeira conta: não pode ser editada, rebaixada nem excluída por ninguém. */
    public const NIVEL_GERAL = 1;
    /** Gerencia outros administradores (exceto o nível 1) e conteúdos. */
    public const NIVEL_GERENTE = 2;
    /** Somente cria, edita e exclui conteúdos. */
    public const NIVEL_EDITOR = 3;

    public const LABELS = [
        self::NIVEL_GERAL => 'Administrador geral',
        self::NIVEL_GERENTE => 'Gerenciador',
        self::NIVEL_EDITOR => 'Editor de conteúdo',
    ];

    /**
     * Níveis que podem ser atribuídos a outras contas (o nível 1 nunca é atribuído).
     */
    public const ASSIGNABLE_LEVELS = [self::NIVEL_GERENTE, self::NIVEL_EDITOR];

    public static function label(int $level): string
    {
        return self::LABELS[$level] ?? 'Desconhecido';
    }

    public static function canManageAdmins(array $admin): bool
    {
        return (int) $admin['nivelAdministrador'] <= self::NIVEL_GERENTE;
    }

    /**
     * O "ator" pode editar/excluir o "alvo"? Nunca o nível 1 e nunca a si mesmo.
     *
     * @param array $actor
     * @param array $target
     * @return boolean
     */
    public static function canManage(array $actor, array $target): bool
    {
        return self::canManageAdmins($actor)
            && (int) $target['nivelAdministrador'] !== self::NIVEL_GERAL
            && (int) $target['id_administrador'] !== (int) $actor['id_administrador'];
    }

    /**
     * Verifica se já existe algum administrador
     *
     * @return boolean
     */
    public static function hasAny(): bool
    {
        $stmt = Database::connect()->query(
            'SELECT 1 FROM administrador LIMIT 1'
        );

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Verifica se o nome do administrador existe no banco
     *
     * @param string $name
     * @return boolean
     */
    public static function usernameExists(string $name): bool
    {
        $stmt = Database::connect()->prepare(
            'SELECT 1 FROM administrador WHERE nome = :nome LIMIT 1'
        );
        $stmt->execute(['nome' => $name]);

        return (bool) $stmt->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connect()->prepare(
            'SELECT id_administrador, nivelAdministrador, nome
             FROM administrador WHERE id_administrador = :id'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    /** Busca com o hash da senha (somente para autenticação). */
    public static function findForLogin(string $name): ?array
    {
        $stmt = Database::connect()->prepare(
            'SELECT id_administrador, nivelAdministrador, nome, senhaHash
             FROM administrador WHERE nome = :nome LIMIT 1'
        );
        $stmt->execute(['nome' => $name]);

        return $stmt->fetch() ?: null;
    }
        
    
    /** @return array<int, array> */
    public static function all(): array
    {
        return Database::connect()->query(
            'SELECT id_administrador, nivelAdministrador, nome
             FROM administrador
             ORDER BY nivelAdministrador ASC, nome ASC'
        )->fetchAll();
    }

    /**
     * Cria um novo administrador no banco
     *
     * @param string $name
     * @param string $password
     * @return integer
     */
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

    /** Atualiza nome e nível; a senha só muda se for informada. */
    public static function update(int $id, string $name, int $level, ?string $password): void
    {
        $sql = 'UPDATE administrador SET nome = :nome, nivelAdministrador = :nivel';
        $params = ['nome' => $name, 'nivel' => $level, 'id' => $id];

        if ($password !== null && $password !== '') {
            $sql .= ', senhaHash = :senhaHash';
            $params['senhaHash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        Database::connect()
            ->prepare($sql . ' WHERE id_administrador = :id AND nivelAdministrador <> ' . self::NIVEL_GERAL)
            ->execute($params);
    }

    public static function delete(int $id): void
    {
        Database::connect()
            ->prepare(
                'DELETE FROM administrador
                 WHERE id_administrador = :id AND nivelAdministrador <> ' . self::NIVEL_GERAL
            )
            ->execute(['id' => $id]);
    }
}
