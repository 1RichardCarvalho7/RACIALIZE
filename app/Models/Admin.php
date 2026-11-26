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
     * Cria o primeiro administrador somente se a tabela ainda estiver vazia. O bloqueio nomeado evita que duas requisições simultâneas criem duas contas de administrador geral durante a configuração inicial.
     *
     * @param string $name
     * @param string $password
     * @return boolean
     */
    public static function createFirst(string $name, string $password): bool
    {
        $pdo = Database::connect();
        $lockName = 'racialize_first_admin';

        $lock = $pdo->prepare('SELECT GET_LOCK(:lockName, 2)');
        $lock->execute(['lockName' => $lockName]);

        // Se o bloqueio não puder ser obtido, o cadastro inicial não prossegue.
        if ((int) $lock->fetchColumn() !== 1) {
            return false;
        }

        try {
            // A consulta é repetida dentro do bloqueio para evitar duplicidade.
            if (self::hasAny()) {
                return false;
            }

            self::create($name, $password, self::NIVEL_GERAL);
            return true;
        } finally {
            $release = $pdo->prepare('SELECT RELEASE_LOCK(:lockName)');
            $release->execute(['lockName' => $lockName]);
        }
    }

    /**
     * Verifica se o nome do administrador existe no banco
     *
     * @param string $name
     * @return boolean
     */
    public static function usernameExists(string $name, ?int $exceptId = null): bool
    {
        $stmt = Database::connect()->prepare(
            'SELECT 1 FROM administrador
             WHERE nome = :nome AND id_administrador <> :id LIMIT 1'
        );
        $stmt->execute(['nome' => $name, 'id' => $exceptId ?? 0]);

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
        string $password,
        int $level = self::NIVEL_EDITOR
    ): int {
        $stmt = Database::connect()->prepare(
            'INSERT INTO administrador (nivelAdministrador, nome, senhaHash)
             VALUES (:nivel, :nome, :senhaHash)'
        );

        $stmt->execute([
            'nivel' => $level,
            'nome' => $name,
            'senhaHash' => password_hash($password, PASSWORD_DEFAULT),
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
