
<?php if(isset($_SESSION["msg"]) && $_SESSION["msg"]["content"]): ?>
<div
    class="alert alert-<?= $_SESSION['msg']['type'] ?> alert-dismissible fade show container"
    role="alert"
    id="alertMessage"
>
    <button
        type="button"
        class="btn-close"
        data-mdb-dismiss="alert"
        aria-label="Close"
    ></button>

    <strong><?= $_SESSION['msg']['content'] ?>!</strong>
</div>

<script>
    // Initialiser l'alerte pour permettre la fermeture
    document.addEventListener('DOMContentLoaded', function() {
        const alertElement = document.getElementById('alertMessage');
        if (alertElement) {
            new mdb.Alert(alertElement);
            
            // Fermeture automatique après 5 secondes
            setTimeout(function() {
                const closeButton = alertElement.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            }, 5000);
        }
    });
</script>
<?php
 unset($_SESSION["msg"]);
endif; 
?>
