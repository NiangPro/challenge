<?php

use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testCreateNotification()
    {
        $user_id = 1;
        $message = "Test notification";
        $type = "info";
        $link = "?page=match";
        
        $result = createNotification($user_id, $message, $type, $link);
        $this->assertTrue($result);
        
        // Vérifier que la notification existe
        $notification = getNotification($result);
        $this->assertNotNull($notification);
        $this->assertEquals($message, $notification->message);
        $this->assertEquals($type, $notification->type);
    }

    public function testMarkNotificationAsRead()
    {
        // Créer une notification de test
        $notification_id = createNotification(1, "Test", "info", "?page=match");
        
        // Marquer comme lue
        $result = markNotificationAsRead($notification_id);
        $this->assertTrue($result);
        
        // Vérifier le statut
        $notification = getNotification($notification_id);
        $this->assertEquals(1, $notification->lu);
    }

    public function testGetUnreadNotifications()
    {
        $user_id = 1;
        
        // Créer quelques notifications de test
        createNotification($user_id, "Test 1", "info", "?page=match");
        createNotification($user_id, "Test 2", "success", "?page=match");
        
        // Récupérer les notifications non lues
        $notifications = getUnreadNotifications($user_id);
        $this->assertCount(2, $notifications);
    }
}
