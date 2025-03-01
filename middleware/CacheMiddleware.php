<?php

class CacheMiddleware {
    private $cacheableTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'svg' => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject'
    ];

    private $cacheControl = [
        'css' => 'public, max-age=31536000', // 1 an
        'js' => 'public, max-age=31536000',
        'jpg' => 'public, max-age=2592000',  // 30 jours
        'jpeg' => 'public, max-age=2592000',
        'png' => 'public, max-age=2592000',
        'gif' => 'public, max-age=2592000',
        'ico' => 'public, max-age=31536000',
        'svg' => 'public, max-age=31536000',
        'woff' => 'public, max-age=31536000',
        'woff2' => 'public, max-age=31536000',
        'ttf' => 'public, max-age=31536000',
        'eot' => 'public, max-age=31536000'
    ];

    public function handle($file) {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        if (!isset($this->cacheableTypes[$extension])) {
            return;
        }

        $this->setHeaders($extension, $file);
        $this->handleConditionalRequests($file);
    }

    private function setHeaders($extension, $file) {
        header("Content-Type: {$this->cacheableTypes[$extension]}");
        header("Cache-Control: {$this->cacheControl[$extension]}");
        
        $etag = $this->generateETag($file);
        header("ETag: \"$etag\"");
        
        $lastModified = filemtime($file);
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
        
        // Activation de la compression GZIP si possible
        if (extension_loaded('zlib')) {
            ini_set('zlib.output_compression', 'On');
        }
    }

    private function handleConditionalRequests($file) {
        $etag = $this->generateETag($file);
        $lastModified = filemtime($file);

        // Vérification ETag
        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            $clientETag = str_replace('"', '', $_SERVER['HTTP_IF_NONE_MATCH']);
            if ($clientETag === $etag) {
                header("HTTP/1.1 304 Not Modified");
                exit;
            }
        }

        // Vérification Last-Modified
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $clientTime = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
            if ($clientTime >= $lastModified) {
                header("HTTP/1.1 304 Not Modified");
                exit;
            }
        }
    }

    private function generateETag($file) {
        return md5(
            filemtime($file) . 
            filesize($file) . 
            pathinfo($file, PATHINFO_BASENAME)
        );
    }
}
