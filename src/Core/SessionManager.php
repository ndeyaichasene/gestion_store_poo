<?php

class SessionManager
{
    /**
     * Démarre la session si aucune n'est active.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function startSession(): void
    {
        self::start();
    }

    /**
     * Initialise la session.
     */
    public static function init(): void
    {
        self::start();
    }

    public static function initSession(): void
    {
        self::start();
    }

    /**
     * Récupère une valeur de la session.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function getSession(string $key, mixed $default = null): mixed
    {
        return self::get($key, $default);
    }

    /**
     * Enregistre une valeur dans la session.
     */
    public static function set(string $key, mixed $value): mixed
    {
        self::start();
        return $_SESSION[$key] = $value;
    }

    public static function setSession(string $key, mixed $value): mixed
    {
        return self::set($key, $value);
    }

    /**
     * Vérifie si une clé existe en session.
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function hasSession(string $key): bool
    {
        return self::has($key);
    }

    /**
     * Supprime une clé de la session.
     */
    public static function unset(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function unsetSession(string $key): void
    {
        self::unset($key);
    }

    public static function remove(string $key): void
    {
        self::unset($key);
    }

    /**
     * Détruit la session courante.
     */
    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
    }

    public static function destroy_session(): void
    {
        self::destroy();
    }

    /**
     * Récupère toutes les données de session.
     */
    public static function all(): array
    {
        self::start();
        return $_SESSION ?? [];
    }

    /**
     * Vide toutes les variables de session sans détruire l'identifiant.
     */
    public static function clear(): void
    {
        self::start();
        $_SESSION = [];
    }
}
