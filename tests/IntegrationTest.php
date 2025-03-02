<?php

use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testCompleteTournamentFlow()
    {
        // Test du flux complet d'un tournoi
        
        // 1. Créer un challenge
        $challenge_id = createChallenge("Test Tournament", date('Y-m-d'), 1);
        $this->assertNotNull($challenge_id);
        
        // 2. Ajouter des participants
        $participant1 = 1;
        $participant2 = 2;
        $participant3 = 3;
        $participant4 = 4;
        
        // 3. Créer les matchs du premier tour
        $match1 = ajouterMatch($participant1, $participant2, $challenge_id);
        $match2 = ajouterMatch($participant3, $participant4, $challenge_id);
        
        // 4. Faire des prédictions
        $user_id = 1;
        $this->assertTrue(ajouterPrediction($match1, $participant1, $user_id));
        $this->assertTrue(ajouterPrediction($match2, $participant3, $user_id));
        
        // 5. Enregistrer les résultats des matchs
        $this->assertTrue(gagner($match1, $participant1));
        $this->assertTrue(gagner($match2, $participant3));
        
        // 6. Vérifier la création du match final
        $matches = matches($challenge_id);
        $this->assertCount(1, $matches);
        
        // 7. Vérifier les notifications
        $notifications = getNotifications($user_id);
        $this->assertNotEmpty($notifications);
    }

    public function testUserStatistics()
    {
        // Test des statistiques utilisateur
        $user_id = 1;
        
        // 1. Créer quelques matchs et prédictions
        $challenge_id = createChallenge("Stats Test", date('Y-m-d'), 1);
        $match_id = ajouterMatch(1, 2, $challenge_id);
        
        // 2. Faire une prédiction
        ajouterPrediction($match_id, 1, $user_id);
        
        // 3. Terminer le match
        gagner($match_id, 1);
        
        // 4. Vérifier les statistiques
        $stats = getUserStats($user_id);
        $this->assertNotNull($stats);
        $this->assertEquals(1, $stats->predictions_correctes);
    }

    protected function tearDown(): void
    {
        // Nettoyer les données de test
        cleanupTestData();
    }
}
