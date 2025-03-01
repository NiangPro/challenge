# Exemples de Code

## 1. Création d'un Challenge

```php
// Exemple d'utilisation du ChallengeController
$challenge = new ChallengeController();

// Création d'un nouveau challenge
$data = [
    'title' => 'Challenge PHP 2025',
    'description' => 'Créer une API REST complète',
    'start_date' => '2025-03-15',
    'end_date' => '2025-04-15',
    'criteria' => [
        'code_quality' => 40,
        'performance' => 30,
        'innovation' => 30
    ]
];

$result = $challenge->create($data);
```

## 2. Gestion des Cohortes

```php
// Exemple d'utilisation du CohorteController
$cohorte = new CohorteController();

// Création d'une cohorte
$cohorteData = [
    'name' => 'Promo 2025',
    'description' => 'Développeurs Full Stack',
    'start_date' => '2025-01-01'
];

$newCohorte = $cohorte->create($cohorteData);

// Ajout de participants
$participants = [
    ['id' => 1, 'name' => 'John Doe'],
    ['id' => 2, 'name' => 'Jane Smith']
];

foreach ($participants as $participant) {
    $cohorte->addParticipant($newCohorte->id, $participant['id']);
}
```

## 3. Système de Tirage

```php
// Exemple d'utilisation du MatchController
$match = new MatchController();

// Configuration du tirage
$config = [
    'cohorte_id' => 1,
    'challenge_id' => 1,
    'rules' => [
        'max_matches' => 3,
        'avoid_previous_matches' => true
    ]
];

// Effectuer le tirage
$matches = $match->createTirage($config);

// Enregistrer les résultats
foreach ($matches as $match) {
    $match->save();
}
```

## 4. API Endpoints

```php
// Exemple de route API pour les challenges
Route::group(['prefix' => 'api'], function () {
    Route::get('/challenges', 'ChallengeController@index');
    Route::post('/challenges', 'ChallengeController@store');
    Route::put('/challenges/{id}', 'ChallengeController@update');
    Route::delete('/challenges/{id}', 'ChallengeController@destroy');
});
```

## 5. Validation des Données

```php
// Exemple de validation
class ChallengeValidator {
    public static function validate($data) {
        $rules = [
            'title' => 'required|min:3|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ];

        return Validator::make($data, $rules);
    }
}
```

## 6. Gestion des Erreurs

```php
// Exemple de gestion d'erreurs
try {
    $challenge = Challenge::findOrFail($id);
    $challenge->update($data);
} catch (ModelNotFoundException $e) {
    return response()->json([
        'error' => 'Challenge non trouvé'
    ], 404);
} catch (Exception $e) {
    return response()->json([
        'error' => 'Erreur serveur'
    ], 500);
}
```
