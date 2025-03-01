# Documentation Technique

## Structure MVC

### Controllers
Les contrôleurs gèrent la logique métier de l'application :

#### ChallengeController
```php
/**
 * Gère les opérations liées aux challenges
 * @method create() Crée un nouveau challenge
 * @method update() Met à jour un challenge existant
 * @method delete() Supprime un challenge
 * @method list() Liste tous les challenges
 */
```

#### CohorteController
```php
/**
 * Gère les opérations liées aux cohortes
 * @method create() Crée une nouvelle cohorte
 * @method addParticipant() Ajoute un participant
 * @method removeParticipant() Retire un participant
 */
```

#### MatchController
```php
/**
 * Gère les opérations de match et tirage
 * @method createMatch() Crée un nouveau match
 * @method updateScore() Met à jour le score
 * @method getTirageResults() Obtient les résultats du tirage
 */
```

### Models
Les modèles représentent la structure des données :

```php
/**
 * @table challenges
 * @property int $id
 * @property string $title
 * @property string $description
 * @property datetime $start_date
 * @property datetime $end_date
 */
```

## Base de données

### Structure des tables

#### Table: challenges
- id (INT, PK)
- title (VARCHAR)
- description (TEXT)
- start_date (DATETIME)
- end_date (DATETIME)

#### Table: cohortes
- id (INT, PK)
- name (VARCHAR)
- description (TEXT)
- created_at (DATETIME)

#### Table: matches
- id (INT, PK)
- challenge_id (INT, FK)
- participant1_id (INT, FK)
- participant2_id (INT, FK)
- score (JSON)

## API Endpoints

### Challenges
- GET /api/challenges - Liste tous les challenges
- POST /api/challenges - Crée un nouveau challenge
- PUT /api/challenges/{id} - Met à jour un challenge
- DELETE /api/challenges/{id} - Supprime un challenge

### Cohortes
- GET /api/cohortes - Liste toutes les cohortes
- POST /api/cohortes - Crée une nouvelle cohorte
- PUT /api/cohortes/{id} - Met à jour une cohorte

### Matches
- GET /api/matches - Liste tous les matches
- POST /api/matches/tirage - Effectue un nouveau tirage
- PUT /api/matches/{id}/score - Met à jour le score
