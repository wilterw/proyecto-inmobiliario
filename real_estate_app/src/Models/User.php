<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        return Database::fetch($sql, ['email' => $email]);
    }

    public static function findByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM users WHERE username = :username AND is_active = 1";
        return Database::fetch($sql, ['username' => $username]);
    }

    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = :id AND is_active = 1";
        return Database::fetch($sql, ['id' => $id]);
    }

    public static function getAll(): array
    {
        $sql = "SELECT id, username, email, full_name, role, is_active, last_login, created_at FROM users ORDER BY created_at DESC";
        return Database::fetchAll($sql);
    }

    public static function create(array $data): int
    {
        // Hash de la contraseña
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return Database::insert('users', $data);
    }

    public static function update(int $id, array $data): bool
    {
        // Hash de la contraseña si se está actualizando
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            // Si no se proporciona contraseña, no la actualizamos
            unset($data['password']);
        }

        $updated = Database::update('users', $data, ['id' => $id]);
        return $updated > 0;
    }

    public static function delete(int $id): bool
    {
        // Soft delete - marcamos como inactivo
        $updated = Database::update('users', ['is_active' => 0], ['id' => $id]);
        return $updated > 0;
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function updateLastLogin(int $id): void
    {
        Database::update('users', ['last_login' => date('Y-m-d H:i:s')], ['id' => $id]);
    }

    public static function createSession(int $userId, string $sessionId, string $ipAddress, string $userAgent): void
    {
        $expiresAt = date('Y-m-d H:i:s', time() + 86400); // 24 horas
        
        Database::insert('user_sessions', [
            'id' => $sessionId,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'expires_at' => $expiresAt
        ]);
    }

    public static function getSession(string $sessionId): ?array
    {
        $sql = "
            SELECT s.*, u.id as user_id, u.username, u.email, u.full_name, u.role
            FROM user_sessions s
            JOIN users u ON s.user_id = u.id
            WHERE s.id = :session_id AND s.expires_at > NOW() AND u.is_active = 1
        ";

        return Database::fetch($sql, ['session_id' => $sessionId]);
    }

    public static function deleteSession(string $sessionId): void
    {
        Database::delete('user_sessions', ['id' => $sessionId]);
    }

    public static function cleanExpiredSessions(): void
    {
        Database::query("DELETE FROM user_sessions WHERE expires_at < NOW()");
    }

    public static function getUserStats(): array
    {
        $stats = [];

        // Total de usuarios activos
        $sql = "SELECT COUNT(*) as total FROM users WHERE is_active = 1";
        $stats['active_users'] = Database::fetch($sql)['total'];

        // Super administradores
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'super_admin' AND is_active = 1";
        $stats['super_admins'] = Database::fetch($sql)['total'];

        // Administradores
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'admin' AND is_active = 1";
        $stats['admins'] = Database::fetch($sql)['total'];

        // Últimos logins (últimos 30 días)
        $sql = "SELECT COUNT(*) as total FROM users WHERE last_login > DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $stats['recent_logins'] = Database::fetch($sql)['total'];

        return $stats;
    }
}