<?php

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testLoginSuccess()
    {
        // Test de connexion réussie
        $email = 'test@example.com';
        $password = 'password123';
        
        // Créer un utilisateur de test dans la base de données
        $user_id = createTestUser($email, password_hash($password, PASSWORD_DEFAULT));
        
        $result = login($email, $password);
        $this->assertTrue($result);
        $this->assertNotNull($_SESSION['user']);
        $this->assertEquals($email, $_SESSION['user']->email);
    }

    public function testLoginFailure()
    {
        // Test de connexion échouée
        $result = login('invalid@example.com', 'wrongpassword');
        $this->assertFalse($result);
        $this->assertArrayNotHasKey('user', $_SESSION);
    }

    public function testLogout()
    {
        // Simuler un utilisateur connecté
        $_SESSION['user'] = (object)[
            'id' => 1,
            'email' => 'test@example.com'
        ];
        
        // Test de déconnexion
        logout();
        $this->assertArrayNotHasKey('user', $_SESSION);
    }

    protected function tearDown(): void
    {
        // Nettoyer les données de test
        cleanupTestUser();
    }
}
