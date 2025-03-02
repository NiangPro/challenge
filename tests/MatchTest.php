<?php

use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    protected function setUp(): void
    {
        // Réinitialiser l'état avant chaque test
        $_SESSION = [];
    }

    public function testCreationMatch()
    {
        // Test de création d'un match
        $id_part1 = 1;
        $id_part2 = 2;
        $challenge_id = 1;
        
        $result = ajouterMatch($id_part1, $id_part2, $challenge_id);
        $this->assertTrue($result);
        
        // Vérifier que le match existe
        $match = getMatch($result);
        $this->assertNotNull($match);
        $this->assertEquals($id_part1, $match->participant1_id);
        $this->assertEquals($id_part2, $match->participant2_id);
    }

    public function testGagnerMatch()
    {
        // Créer un match de test
        $match_id = ajouterMatch(1, 2, 1);
        
        // Tester la fonction gagner
        $result = gagner($match_id, 1);
        $this->assertTrue($result);
        
        // Vérifier que le gagnant est bien enregistré
        $match = getMatch($match_id);
        $this->assertEquals(1, $match->gagnant_id);
        $this->assertEquals(1, $match->statut);
    }

    public function testPredictionMatch()
    {
        // Créer un utilisateur de test
        $_SESSION['user'] = (object)[
            'id' => 1,
            'nom' => 'Test',
            'prenom' => 'User'
        ];
        
        // Créer un match de test
        $match_id = ajouterMatch(1, 2, 1);
        
        // Faire une prédiction
        $result = ajouterPrediction($match_id, 1, $_SESSION['user']->id);
        $this->assertTrue($result);
        
        // Vérifier la prédiction
        $prediction = getPrediction($match_id, $_SESSION['user']->id);
        $this->assertNotNull($prediction);
        $this->assertEquals(1, $prediction->participant_id);
    }
}
