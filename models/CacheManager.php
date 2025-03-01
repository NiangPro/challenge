<?php
declare(strict_types=1);

use Redis;

/**
 * Gestionnaire de cache avec support Redis et fichier
 */
class CacheManager {
    private $redis = null;
    private bool $useFileCache = false;
    private int $defaultTTL = 3600; // 1 heure
    private string $prefix = 'challenge:';
    private string $cacheDir;

    public function __construct() {
        $this->cacheDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cache';
        $this->initializeCache();
    }

    private function initializeCache(): void {
        if (!extension_loaded('redis') || !class_exists('Redis')) {
            $this->useFileCache = true;
            $this->ensureCacheDirectory();
            return;
        }

        try {
            $redisClass = 'Redis';
            $this->redis = new $redisClass();
            $connected = @$this->redis->connect('127.0.0.1', 6379, 2.0); // Timeout de 2 secondes
            
            if (!$connected) {
                throw new Exception('Impossible de se connecter à Redis');
            }
            
            if (method_exists($this->redis, 'setOption')) {
                $this->redis->setOption($redisClass::OPT_SERIALIZER, $redisClass::SERIALIZER_PHP);
            }
            
            $this->redis->ping();
        } catch (Throwable $e) {
            error_log("Redis non disponible: " . $e->getMessage());
            $this->redis = null;
            $this->useFileCache = true;
            $this->ensureCacheDirectory();
        }
    }

    private function ensureCacheDirectory(): void {
        if (!file_exists($this->cacheDir)) {
            $created = @mkdir($this->cacheDir, 0777, true);
            if (!$created) {
                throw new RuntimeException("Impossible de créer le dossier de cache: " . $this->cacheDir);
            }
        }

        if (!is_writable($this->cacheDir)) {
            throw new RuntimeException("Le dossier de cache n'est pas accessible en écriture: " . $this->cacheDir);
        }
    }

    public function get(string $key) {
        $key = $this->sanitizeKey($key);

        if ($this->redis !== null) {
            try {
                $value = $this->redis->get($this->prefix . $key);
                return $value !== false ? $value : null;
            } catch (Throwable $e) {
                error_log("Erreur Redis get: " . $e->getMessage());
                return $this->getFromFile($key);
            }
        }

        return $this->getFromFile($key);
    }

    public function set(string $key, $value, ?int $ttl = null): bool {
        $key = $this->sanitizeKey($key);
        $ttl = $ttl ?? $this->defaultTTL;

        if ($this->redis !== null) {
            try {
                return $this->redis->setex($this->prefix . $key, $ttl, $value);
            } catch (Throwable $e) {
                error_log("Erreur Redis set: " . $e->getMessage());
                return $this->setToFile($key, $value, $ttl);
            }
        }

        return $this->setToFile($key, $value, $ttl);
    }

    public function delete(string $key): bool {
        $key = $this->sanitizeKey($key);

        if ($this->redis !== null) {
            try {
                return (bool) $this->redis->del($this->prefix . $key);
            } catch (Throwable $e) {
                error_log("Erreur Redis delete: " . $e->getMessage());
                return $this->deleteFile($key);
            }
        }

        return $this->deleteFile($key);
    }

    public function clear(): bool {
        if ($this->redis !== null) {
            try {
                $keys = $this->redis->keys($this->prefix . '*');
                if (!empty($keys)) {
                    return (bool) $this->redis->del($keys);
                }
                return true;
            } catch (Throwable $e) {
                error_log("Erreur Redis clear: " . $e->getMessage());
            }
        }

        return $this->clearFileCache();
    }

    private function getFromFile(string $key) {
        $filename = $this->getCacheFilename($key);
        if (!file_exists($filename)) {
            return null;
        }

        try {
            $content = file_get_contents($filename);
            if ($content === false) {
                return null;
            }

            $data = @unserialize($content);
            if ($data === false) {
                @unlink($filename);
                return null;
            }

            if (!isset($data['expires']) || !isset($data['value'])) {
                @unlink($filename);
                return null;
            }

            if ($data['expires'] < time()) {
                @unlink($filename);
                return null;
            }

            return $data['value'];
        } catch (Throwable $e) {
            error_log("Erreur lecture cache fichier: " . $e->getMessage());
            return null;
        }
    }

    private function setToFile(string $key, $value, int $ttl): bool {
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];

        try {
            $filename = $this->getCacheFilename($key);
            return file_put_contents($filename, serialize($data), LOCK_EX) !== false;
        } catch (Throwable $e) {
            error_log("Erreur écriture cache fichier: " . $e->getMessage());
            return false;
        }
    }

    private function deleteFile(string $key): bool {
        try {
            $filename = $this->getCacheFilename($key);
            if (file_exists($filename)) {
                return @unlink($filename);
            }
            return true;
        } catch (Throwable $e) {
            error_log("Erreur suppression cache fichier: " . $e->getMessage());
            return false;
        }
    }

    private function clearFileCache(): bool {
        try {
            $files = glob($this->cacheDir . DIRECTORY_SEPARATOR . '*.cache');
            if ($files === false) {
                return false;
            }

            $success = true;
            foreach ($files as $file) {
                if (is_file($file)) {
                    $success = $success && @unlink($file);
                }
            }
            return $success;
        } catch (Throwable $e) {
            error_log("Erreur nettoyage cache fichier: " . $e->getMessage());
            return false;
        }
    }

    private function getCacheFilename(string $key): string {
        return $this->cacheDir . DIRECTORY_SEPARATOR . md5($this->prefix . $key) . '.cache';
    }

    private function sanitizeKey(string $key): string {
        return preg_replace('/[^a-zA-Z0-9_-]/', '', $key);
    }

    public function remember(string $key, callable $callback, ?int $ttl = null) {
        $value = $this->get($key);
        if ($value !== null) {
            return $value;
        }

        $value = $callback();
        $this->set($key, $value, $ttl);
        return $value;
    }

    public function getCacheKey(string $prefix, array $params = []): string {
        return $prefix . ':' . md5(serialize($params));
    }

    public function isRedisAvailable(): bool {
        return $this->redis !== null;
    }

    public function getDefaultTTL(): int {
        return $this->defaultTTL;
    }

    public function setDefaultTTL(int $ttl): void {
        if ($ttl < 0) {
            throw new InvalidArgumentException('TTL doit être positif');
        }
        $this->defaultTTL = $ttl;
    }
}
