<?php

class AssetOptimizer {
    private $baseDir;
    private $cssDir;
    private $jsDir;
    private $imgDir;

    public function __construct() {
        $this->baseDir = dirname(__DIR__);
        $this->cssDir = $this->baseDir . '/css';
        $this->jsDir = $this->baseDir . '/js';
        $this->imgDir = $this->baseDir . '/img';
    }

    public function optimizeAll() {
        $this->optimizeCSS();
        $this->optimizeJS();
        $this->optimizeImages();
        $this->generateManifest();
    }

    private function optimizeCSS() {
        $files = glob($this->cssDir . '/*.css');
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            // Suppression des commentaires
            $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
            
            // Suppression des espaces multiples
            $content = preg_replace('/\s+/', ' ', $content);
            
            // Suppression des espaces autour des caractères spéciaux
            $content = preg_replace('/\s*([\{\}\:\;\,])\s*/', '$1', $content);
            
            // Création du fichier minifié
            $minFile = str_replace('.css', '.min.css', $file);
            file_put_contents($minFile, $content);
        }
    }

    private function optimizeJS() {
        $files = glob($this->jsDir . '/*.js');
        foreach ($files as $file) {
            if (strpos($file, '.min.js') !== false) {
                continue;
            }
            
            $content = file_get_contents($file);
            
            // Suppression des commentaires sur une ligne
            $content = preg_replace('/\/\/.*$/m', '', $content);
            
            // Suppression des commentaires multi-lignes
            $content = preg_replace('/\/\*.*?\*\//s', '', $content);
            
            // Suppression des espaces inutiles
            $content = preg_replace('/\s+/', ' ', $content);
            
            // Création du fichier minifié
            $minFile = str_replace('.js', '.min.js', $file);
            file_put_contents($minFile, $content);
        }
    }

    private function optimizeImages() {
        $extensions = ['jpg', 'jpeg', 'png', 'gif'];
        foreach ($extensions as $ext) {
            $files = glob($this->imgDir . '/*.' . $ext);
            foreach ($files as $file) {
                $this->optimizeImage($file, $ext);
            }
        }
    }

    private function optimizeImage($file, $ext) {
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file);
                imagejpeg($image, $file, 85); // Compression 85%
                break;
                
            case 'png':
                $image = imagecreatefrompng($file);
                imagesavealpha($image, true);
                imagepng($image, $file, 9); // Compression maximale
                break;
                
            case 'gif':
                $image = imagecreatefromgif($file);
                imagegif($image, $file);
                break;
        }
        
        if (isset($image)) {
            imagedestroy($image);
        }
    }

    private function generateManifest() {
        $manifest = [
            'css' => $this->getFileVersions($this->cssDir, 'css'),
            'js' => $this->getFileVersions($this->jsDir, 'js'),
            'generated' => date('Y-m-d H:i:s')
        ];

        file_put_contents(
            $this->baseDir . '/assets-manifest.json',
            json_encode($manifest, JSON_PRETTY_PRINT)
        );
    }

    private function getFileVersions($dir, $ext) {
        $versions = [];
        $files = glob($dir . '/*.' . $ext);
        
        foreach ($files as $file) {
            $key = basename($file);
            $versions[$key] = hash_file('md5', $file);
        }
        
        return $versions;
    }
}

// Exécution de l'optimisation
$optimizer = new AssetOptimizer();
$optimizer->optimizeAll();

echo "Optimisation des ressources terminée.\n";
