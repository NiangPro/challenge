<div class="card container col-md-10 mt-5 mb-5">
  <div class="card-body">
    <h5 class="card-title text-center">  Bienvenue à l'Application de Duel de Programmation
    </h5>
    <p class="card-text text-center">Relevez le défi avec un autre étudiant !</p>
    <form class="container col-md-4">
      <!-- Email input -->
      <div data-mdb-input-init class="form-outline mb-4">
        <input type="text" name="prenom" id="form1Example1" class="form-control" />
        <label class="form-label" for="form1Example1">Prenom</label>
      </div>

      <!-- Password input -->
      <div data-mdb-input-init class="form-outline mb-4">
        <input type="text" id="form1Example2"  name="nom" class="form-control" />
        <label class="form-label" for="form1Example2">Nom</label>
      </div>

      <div data-mdb-input-init class="form-group mb-4">
        <label  for="inlineFormSelectPref">Classe</label>
        <select data-mdb-select-init class="form-control select">
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
          <option value="4">Four</option>
          <option value="5">Five</option>
          <option value="6">Six</option>
          <option value="7">Seven</option>
          <option value="8">Eight</option>
        </select>
      </div>

      <div data-mdb-input-init class="form-group mb-4">
        <label  for="inlineFormSelectPref">Choisir le langage :</label>
        <select data-mdb-select-init class="form-control select">
          <option value="html">HTML</option>
          <option value="css">CSS</option>
          <option value="bootstrap">Bootstrap</option>
          <option value="js">JavaScript</option>
          <option value="php">PHP</option>
        </select>
      </div>
      <!-- Submit button -->
      <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block">Démarrer le defi</button>
    </form>
  </div>
</div>