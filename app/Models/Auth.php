<?php

declare(strict_types=1);

namespace App\Models;


/** Sessão, login, CSRF e mensagens flash. */
class Auth
{
    private static ?array $user = null;
    private static bool $userLoaded = false;

    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_set_cookie_params([
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function login(array $admin): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id_administrador'];
        self::$userLoaded = false;
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        session_regenerate_id(true);
        self::$user = null;
        self::$userLoaded = false;
    }

    /**
     * Administrador logado, lido do banco a cada requisição:
     * assim, mudanças de nível ou exclusão valem imediatamente.
     */
    public static function user(): ?array
    {
        if (self::$userLoaded) {
            return self::$user;
        }

        self::start();
        self::$userLoaded = true;
        self::$user = null;

        $id = $_SESSION['admin_id'] ?? null;
        if (is_int($id)) {
            self::$user = Admin::find($id);

            if (self::$user === null) {
                unset($_SESSION['admin_id']);
            }
        }

        return self::$user;
    }

    // ----- CSRF -----

    public static function csrfToken(): string
    {
        self::start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function csrfValid(): bool
    {
        self::start();

        $session = $_SESSION['csrf_token'] ?? '';
        $form = $_POST['csrf_token'] ?? '';

        return is_string($session) && $session !== ''
            && is_string($form) && hash_equals($session, $form);
    }

    // ----- Flash -----

    public static function flash(string $type, string $message): void
    {
        self::start();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function pullFlash(): ?array
    {
        self::start();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return $flash;
    }
}
