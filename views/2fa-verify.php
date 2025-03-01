<?php require_once 'includes/entete.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Vérification en deux étapes</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="?page=verify-2fa">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-group">
                            <label for="code">Code de vérification</label>
                            <input type="text" class="form-control" id="code" name="code" 
                                   required pattern="[0-9]{6}" maxlength="6"
                                   placeholder="Entrez le code reçu par email">
                            <small class="form-text text-muted">
                                Un code à 6 chiffres a été envoyé à votre adresse email.
                            </small>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" name="verify_2fa" class="btn btn-primary btn-block">
                                Vérifier
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="?page=resend-2fa" class="text-decoration-none">
                                Je n'ai pas reçu de code
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/pied.php'; ?>
