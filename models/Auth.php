<?php

class Auth {
    private $db;
    private $jwtSecret = 'your-secret-key'; // À stocker dans un fichier de configuration
    
    public function __construct() {
        $this->db = new PDO("mysql:host=localhost;dbname=your_database", "user", "password");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        return $user;
    }

    public function getLoginAttempts($ip) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND timestamp > ?");
        $stmt->execute([$ip, time() - 900]); // 15 minutes
        return $stmt->fetchColumn();
    }

    public function incrementLoginAttempts($ip) {
        $stmt = $this->db->prepare("INSERT INTO login_attempts (ip, timestamp) VALUES (?, ?)");
        $stmt->execute([$ip, time()]);
    }

    public function resetLoginAttempts($ip) {
        $stmt = $this->db->prepare("DELETE FROM login_attempts WHERE ip = ?");
        $stmt->execute([$ip]);
    }

    public function getLastLoginAttempt($ip) {
        $stmt = $this->db->prepare("SELECT MAX(timestamp) FROM login_attempts WHERE ip = ?");
        $stmt->execute([$ip]);
        return $stmt->fetchColumn();
    }

    public function generate2FACode() {
        return sprintf("%06d", random_int(0, 999999));
    }

    public function send2FACode($email, $code) {
        // Implémenter l'envoi d'email avec le code
        // Utiliser une bibliothèque d'envoi d'email comme PHPMailer
        mail($email, "Code d'authentification", "Votre code : " . $code);
    }

    public function generateJWTToken($user) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            'user_id' => $user->id,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + (60 * 60) // 1 heure
        ]);

        $base64Header = $this->base64UrlEncode($header);
        $base64Payload = $this->base64UrlEncode($payload);

        $signature = hash_hmac('sha256', 
            $base64Header . "." . $base64Payload, 
            $this->jwtSecret, 
            true
        );
        $base64Signature = $this->base64UrlEncode($signature);

        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }

    public function verifyJWTToken($token) {
        $parts = explode('.', $token);
        if (count($parts) != 3) {
            return false;
        }

        $header = json_decode($this->base64UrlDecode($parts[0]));
        $payload = json_decode($this->base64UrlDecode($parts[1]));
        $signature = $this->base64UrlDecode($parts[2]);

        $verificationSignature = hash_hmac('sha256',
            $parts[0] . "." . $parts[1],
            $this->jwtSecret,
            true
        );

        if ($signature !== $verificationSignature) {
            return false;
        }

        if ($payload->exp < time()) {
            return false;
        }

        return $payload;
    }

    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
}
