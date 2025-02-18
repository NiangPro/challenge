
<?php if(isset($_SESSION["msg"]) && $_SESSION["msg"]["content"]): ?>
<div
    class="alert alert-<?= $_SESSION['msg']['type'] ?> alert-dismissible fade show container"
    role="alert"
>
    <button
        type="button"
        class="btn-close"
        data-mdb-dismiss="alert"
        aria-label="Close"
    ></button>

    <strong><?= $_SESSION['msg']['content'] ?>!</strong>
</div>
<?php
 unset($_SESSION["msg"]);
endif; 
?>
