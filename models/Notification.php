<?php

class Notification {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Crée une nouvelle notification
     * 
     * @param int $user_id ID de l'utilisateur (0 pour tous les utilisateurs)
     * @param string $message Message de la notification
     * @param string $type Type de notification (info, success, warning, danger)
     * @param string $link Lien optionnel pour rediriger l'utilisateur
     * @return bool Succès de l'opération
     */
    public function create($user_id, $message, $type = 'info', $link = null) {
        try {
            $q = $this->db->prepare("INSERT INTO notifications (user_id, message, type, link) VALUES (:user_id, :message, :type, :link)");
            return $q->execute([
                "user_id" => $user_id,
                "message" => $message,
                "type" => $type,
                "link" => $link
            ]);
        } catch (PDOException $th) {
            setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
            return false;
        }
    }

    /**
     * Récupère les notifications d'un utilisateur
     * 
     * @param int $user_id ID de l'utilisateur
     * @param int $limit Nombre maximum de notifications à récupérer
     * @param bool $include_read Inclure les notifications déjà lues
     * @return array Liste des notifications
     */
    public function getForUser($user_id, $limit = 10, $include_read = false) {
        try {
            $sql = "SELECT * FROM notifications WHERE (user_id = :user_id OR user_id = 0)";
            
            if (!$include_read) {
                $sql .= " AND is_read = 0";
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT :limit";
            
            $q = $this->db->prepare($sql);
            $q->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $q->bindParam(':limit', $limit, PDO::PARAM_INT);
            $q->execute();
            
            return $q->fetchAll();
        } catch (PDOException $th) {
            setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
            return [];
        }
    }

    /**
     * Marque une notification comme lue
     * 
     * @param int $id ID de la notification
     * @return bool Succès de l'opération
     */
    public function markAsRead($id) {
        try {
            $q = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id");
            return $q->execute(["id" => $id]);
        } catch (PDOException $th) {
            setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
            return false;
        }
    }

    /**
     * Marque toutes les notifications d'un utilisateur comme lues
     * 
     * @param int $user_id ID de l'utilisateur
     * @return bool Succès de l'opération
     */
    public function markAllAsRead($user_id) {
        try {
            $q = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id OR user_id = 0");
            return $q->execute(["user_id" => $user_id]);
        } catch (PDOException $th) {
            setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
            return false;
        }
    }

    /**
     * Compte le nombre de notifications non lues pour un utilisateur
     * 
     * @param int $user_id ID de l'utilisateur
     * @return int Nombre de notifications non lues
     */
    public function countUnread($user_id) {
        try {
            $q = $this->db->prepare("SELECT COUNT(*) as count FROM notifications WHERE (user_id = :user_id OR user_id = 0) AND is_read = 0");
            $q->execute(["user_id" => $user_id]);
            $result = $q->fetch();
            return $result->count;
        } catch (PDOException $th) {
            setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
            return 0;
        }
    }
}
