<?php

class Logger {
    private $logFile;
    private $db;

    public function __construct() {
        $this->logFile = dirname(__DIR__) . '/logs/auth.log';
        $this->ensureLogDirectoryExists();
        $this->db = new PDO("mysql:host=localhost;dbname=your_database", "user", "password");
    }

    private function ensureLogDirectoryExists() {
        $logDir = dirname($this->logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    public function logInfo($event, array $data = []) {
        $this->log('INFO', $event, $data);
    }

    public function logWarning($event, array $data = []) {
        $this->log('WARNING', $event, $data);
    }

    public function logError($event, array $data = []) {
        $this->log('ERROR', $event, $data);
    }

    private function log($level, $event, array $data = []) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = [
            'timestamp' => $timestamp,
            'level' => $level,
            'event' => $event,
            'data' => json_encode($data),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        // Logging dans la base de données
        $this->logToDatabase($logEntry);

        // Logging dans le fichier
        $this->logToFile($logEntry);
    }

    private function logToDatabase($entry) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO auth_logs 
                (timestamp, level, event, data, ip, user_agent)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $entry['timestamp'],
                $entry['level'],
                $entry['event'],
                $entry['data'],
                $entry['ip'],
                $entry['user_agent']
            ]);
        } catch (PDOException $e) {
            // En cas d'erreur avec la BD, on log uniquement dans le fichier
            error_log("Erreur de logging BD: " . $e->getMessage());
        }
    }

    private function logToFile($entry) {
        $logMessage = sprintf(
            "[%s] %s [%s]: %s - Data: %s - IP: %s - UA: %s\n",
            $entry['timestamp'],
            $entry['level'],
            $entry['event'],
            $entry['data'],
            $entry['ip'],
            $entry['user_agent']
        );

        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    public function getRecentLogs($limit = 100) {
        $stmt = $this->db->prepare("
            SELECT * FROM auth_logs 
            ORDER BY timestamp DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLogsByLevel($level, $limit = 100) {
        $stmt = $this->db->prepare("
            SELECT * FROM auth_logs 
            WHERE level = ?
            ORDER BY timestamp DESC 
            LIMIT ?
        ");
        $stmt->execute([$level, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLogsByIP($ip, $limit = 100) {
        $stmt = $this->db->prepare("
            SELECT * FROM auth_logs 
            WHERE ip = ?
            ORDER BY timestamp DESC 
            LIMIT ?
        ");
        $stmt->execute([$ip, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
