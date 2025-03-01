<?php
require_once 'middleware/CacheMiddleware.php';

$requestPath = $_GET['file'] ?? '';
$basePath = __DIR__;

// Liste des répertoires autorisés pour les ressources statiques
$allowedDirs = [
    'css' => $basePath . '/css',
    'js' => $basePath . '/js',
    'img' => $basePath . '/img',
    'fonts' => $basePath . '/fonts'
];

// Vérification de sécurité
$requestedDir = explode('/', $requestPath)[0];
if (!isset($allowedDirs[$requestedDir])) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access Denied");
}

// Construction du chemin complet
$filePath = realpath($basePath . '/' . $requestPath);
$basePathReal = realpath($basePath);

// Vérification que le fichier est bien dans un répertoire autorisé
if ($filePath === false || strpos($filePath, $basePathReal) !== 0) {
    header("HTTP/1.1 404 Not Found");
    exit("File not found");
}

if (!file_exists($filePath) || !is_file($filePath)) {
    header("HTTP/1.1 404 Not Found");
    exit("File not found");
}

// Application du middleware de cache
$cache = new CacheMiddleware();
$cache->handle($filePath);

// Envoi du fichier
readfile($filePath);
