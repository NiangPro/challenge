<div id="intro" class="bg-image shadow-2-strong">
      <div class="mask d-flex align-items-center h-100">
        <div class="container">
          <?php require_once("views/includes/getmessage.php"); ?>
          <div class="row justify-content-center">
            <div class="col-xl-5 col-md-8">
              <form method="post" autocomplete="off" class="bg-white rounded shadow-5-strong p-5">
                <!-- Email input -->
                <div class="form-outline mb-4" data-mdb-input-init>
                  <input type="email" name="email" id="form1Example1" class="form-control" />
                  <label class="form-label" for="form1Example1">Email</label>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4" data-mdb-input-init>
                  <input type="password" name="mdp" id="form1Example2" class="form-control" />
                  <label class="form-label" for="form1Example2">Mot de passe</label>
                </div>

                <!-- 2 column grid layout for inline styling -->
                <div class="row mb-4">
                  <div class="col d-flex justify-content-center">
                    <!-- Checkbox -->
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked />
                      <label class="form-check-label" for="form1Example3">
                        Se souvenir de moi
                      </label>
                    </div>
                  </div>

                  <div class="col text-center">
                    <!-- Simple link -->
                    <a href="#!">Mot de passe oublié?</a>
                  </div>
                </div>

                <!-- Submit button -->
                <button type="submit" name="login" class="btn btn-primary btn-block" data-mdb-ripple-init>Se connecter</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>