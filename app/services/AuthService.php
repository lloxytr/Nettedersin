<?php

class AuthService
{
    public function __construct(private Repository $repo, private array $config)
    {
    }

    public function ensureSessionRow(): void
    {
        if (!empty($_SESSION['user']['id'])) {
            $this->repo->upsertSession((int)$_SESSION['user']['id'], session_id());
        }
    }

    public function register(string $name, string $email, string $password, string $role = 'student'): int
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Geçerli bir e-posta girin.');
        }
        if (strlen($password) < 8) {
            throw new RuntimeException('Şifre en az 8 karakter olmalı.');
        }
        if ($this->repo->getUserByEmail($email)) {
            throw new RuntimeException('Bu e-posta zaten kayıtlı.');
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        return $this->repo->createUser($name, mb_strtolower($email), $hash, $role);
    }

    public function login(string $email, string $password): array
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if ($this->repo->loginAttemptCount($ip) >= 8) {
            throw new RuntimeException('Çok fazla deneme yaptınız. 15 dakika sonra tekrar deneyin.');
        }

        $user = $this->repo->getUserByEmail(mb_strtolower($email));
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->repo->addLoginAttempt($ip, $email);
            throw new RuntimeException('E-posta veya şifre hatalı.');
        }

        if (($user['status'] ?? 'active') !== 'active') {
            throw new RuntimeException('Hesabınız pasif durumda.');
        }

        $this->repo->clearLoginAttempts($ip);
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        $this->repo->upsertSession((int)$user['id'], session_id());
        $this->repo->logAudit((int)$user['id'], 'auth.login');

        return $_SESSION['user'];
    }

    public function logout(): void
    {
        $uid = $_SESSION['user']['id'] ?? null;
        if ($uid) {
            $this->repo->logAudit((int)$uid, 'auth.logout');
        }
        $_SESSION = [];
        session_destroy();
    }

    public function requestReset(string $email): ?string
    {
        $user = $this->repo->getUserByEmail(mb_strtolower($email));
        if (!$user) {
            return null;
        }

        $token = bin2hex(random_bytes(24));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $this->repo->createResetToken((int)$user['id'], $token, $expiresAt);
        $this->repo->logAudit((int)$user['id'], 'auth.password_reset_requested');
        return $token;
    }

    public function resetPassword(string $token, string $newPassword): void
    {
        if (strlen($newPassword) < 8) {
            throw new RuntimeException('Yeni şifre en az 8 karakter olmalı.');
        }

        $row = $this->repo->getResetToken($token);
        if (!$row) {
            throw new RuntimeException('Token geçersiz veya süresi doldu.');
        }

        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->repo->updatePassword((int)$row['user_id'], $hash);
        $this->repo->consumeResetToken((int)$row['id']);
        $this->repo->logAudit((int)$row['user_id'], 'auth.password_reset_completed');
    }
}
