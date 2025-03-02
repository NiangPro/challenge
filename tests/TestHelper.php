<?php

/**
 * Fonctions helper pour les tests
 */

/**
 * Crée un utilisateur de test
 */
function createTestUser($email, $password) {
    $sql = "INSERT INTO utilisateurs (email, password, nom, prenom) VALUES (?, ?, 'Test', 'User')";
    $stmt = getConnection()->prepare($sql);
    $stmt->execute([$email, $password]);
    return getConnection()->lastInsertId();
}

/**
 * Nettoie les données de test
 */
function cleanupTestData() {
    // Supprimer les données de test
    $tables = ['matches', 'predictions', 'notifications', 'challenges', 'utilisateurs'];
    foreach ($tables as $table) {
        $sql = "DELETE FROM $table WHERE id IN (SELECT id FROM $table ORDER BY id DESC LIMIT 10)";
        getConnection()->exec($sql);
    }
}

/**
 * Crée un challenge de test
 */
function createTestChallenge($nom, $date, $cohorte_id) {
    $sql = "INSERT INTO challenges (nom, date_creation, id_cohorte, statut) VALUES (?, ?, ?, 0)";
    $stmt = getConnection()->prepare($sql);
    $stmt->execute([$nom, $date, $cohorte_id]);
    return getConnection()->lastInsertId();
}

/**
 * Crée un match de test
 */
function createTestMatch($participant1_id, $participant2_id, $challenge_id) {
    $sql = "INSERT INTO matches (participant1_id, participant2_id, challenge_id, statut) VALUES (?, ?, ?, 0)";
    $stmt = getConnection()->prepare($sql);
    $stmt->execute([$participant1_id, $participant2_id, $challenge_id]);
    return getConnection()->lastInsertId();
}

/**
 * Crée une prédiction de test
 */
function createTestPrediction($match_id, $participant_id, $user_id) {
    $sql = "INSERT INTO predictions (match_id, participant_id, user_id) VALUES (?, ?, ?)";
    $stmt = getConnection()->prepare($sql);
    $stmt->execute([$match_id, $participant_id, $user_id]);
    return getConnection()->lastInsertId();
}

/**
 * Crée une notification de test
 */
function createTestNotification($user_id, $message, $type = 'info', $link = null) {
    $sql = "INSERT INTO notifications (user_id, message, type, link) VALUES (?, ?, ?, ?)";
    $stmt = getConnection()->prepare($sql);
    $stmt->execute([$user_id, $message, $type, $link]);
    return getConnection()->lastInsertId();
}
