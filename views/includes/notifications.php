<?php
// Récupérer les notifications non lues pour l'utilisateur connecté
$user_id = isset($_SESSION['user']) ? $_SESSION['user']->id : 0;
$notifications = getUserNotifications($user_id);
$unread_count = countUnreadNotifications($user_id);
?>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownNotifications" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-bell"></i>
        <?php if ($unread_count > 0): ?>
            <span class="badge rounded-pill badge-notification bg-danger"><?= $unread_count ?></span>
        <?php endif; ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownNotifications" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
        <div class="p-2 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Notifications</h6>
            <?php if ($unread_count > 0): ?>
                <a href="?page=match&mark_all_read" class="text-primary small">Tout marquer comme lu</a>
            <?php endif; ?>
        </div>
        <li><hr class="dropdown-divider" /></li>
        
        <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notification): ?>
                <li>
                    <a class="dropdown-item <?= $notification->is_read ? 'text-muted' : 'fw-bold' ?>" 
                       href="?page=match&mark_read&notification_id=<?= $notification->id ?>&redirect=<?= urlencode($notification->link) ?>">
                        <div class="d-flex flex-column">
                            <div class="notification-text">
                                <?= $notification->message ?>
                            </div>
                            <div class="small text-muted mt-1">
                                <?= date('d/m/Y H:i', strtotime($notification->created_at)) ?>
                            </div>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider" /></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li><div class="dropdown-item text-center">Aucune notification</div></li>
        <?php endif; ?>
    </ul>
</li>
