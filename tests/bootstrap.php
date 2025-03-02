<?php

// Initialisation de l'autoloader
require_once __DIR__ . '/../models/database.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../models/Stats.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/CacheManager.php';

// Inclure les helpers de test
require_once __DIR__ . '/TestHelper.php';

// Configuration de l'environnement de test
$_ENV['APP_ENV'] = 'testing';

// Initialisation de la session pour les tests
if (!isset($_SESSION)) {
    session_start();
}

// Nettoyer les données de test au démarrage
cleanupTestData();
