<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class AuthService
{
    private const SESSION_KEY = 'inmobiliaria_user_session';

    public function login(string $username, string $password): bool
    {
        // Buscar usuario por username o email
        $user = User::findByUsername($username) ?: User::findByEmail($username);

        if (!$user || !User::verifyPassword($password, $user['password'])) {
            return false;
        }

        // Crear sesión
        $sessionId = $this->generateSessionId();
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        User::createSession($user['id'], $sessionId, $ipAddress, $userAgent);
        User::updateLastLogin($user['id']);

        // Guardar sesión en cookie
        $this->setSessionCookie($sessionId);

        return true;
    }

    public function logout(): void
    {
        $sessionId = $this->getSessionId();
        
        if ($sessionId) {
            User::deleteSession($sessionId);
        }

        $this->clearSessionCookie();
    }

    public function isAuthenticated(): bool
    {
        $sessionId = $this->getSessionId();
        
        if (!$sessionId) {
            return false;
        }

        $session = User::getSession($sessionId);
        return $session !== null;
    }

    public function getCurrentUser(): ?array
    {
        $sessionId = $this->getSessionId();
        
        if (!$sessionId) {
            return null;
        }

        return User::getSession($sessionId);
    }

    private function generateSessionId(): string
    {
        return bin2hex(random_bytes(32));
    }

    private function getSessionId(): ?string
    {
        return $_COOKIE[self::SESSION_KEY] ?? null;
    }

    private function setSessionCookie(string $sessionId): void
    {
        $expires = time() + 86400; // 24 horas
        $path = '/';
        $domain = '';
        $secure = isset($_SERVER['HTTPS']);
        $httpOnly = true;

        setcookie(self::SESSION_KEY, $sessionId, $expires, $path, $domain, $secure, $httpOnly);
    }

    private function clearSessionCookie(): void
    {
        setcookie(self::SESSION_KEY, '', time() - 3600, '/');
    }

    public function cleanExpiredSessions(): void
    {
        User::cleanExpiredSessions();
    }
}