# Guide de Contribution

## Comment Contribuer

### 1. Préparer son environnement

1. Forker le projet
2. Cloner votre fork
```bash
git clone https://github.com/votre-username/challenge.git
```
3. Configurer le remote upstream
```bash
git remote add upstream https://github.com/original/challenge.git
```

### 2. Conventions de Code

#### Style de Code PHP
- Utiliser PSR-12
- Indentation : 4 espaces
- Nommage en camelCase pour les méthodes et variables
- Nommage en PascalCase pour les classes
- Commentaires en français

#### JavaScript
- Utiliser ESLint
- Suivre le style Airbnb
- Utiliser const/let (pas de var)
- Documenter avec JSDoc

#### Base de données
- Tables en minuscules, au pluriel
- Clés primaires : id
- Clés étrangères : nom_table_id
- Timestamps : created_at, updated_at

### 3. Processus de Contribution

1. Créer une branche
```bash
git checkout -b feature/nom-feature
```

2. Faire les modifications
- Écrire des tests
- Suivre les conventions de code
- Documenter les changements

3. Commiter
```bash
git commit -m "type(scope): description"
```
Types : feat, fix, docs, style, refactor, test, chore

4. Pousser les modifications
```bash
git push origin feature/nom-feature
```

5. Créer une Pull Request
- Titre clair et descriptif
- Description détaillée des changements
- Référencer les issues concernées

### 4. Tests

1. Tests Unitaires
```bash
php vendor/bin/phpunit
```

2. Tests d'Intégration
```bash
php vendor/bin/phpunit --testsuite integration
```

### 5. Documentation

- Mettre à jour la documentation si nécessaire
- Ajouter des commentaires PHPDoc
- Documenter les nouvelles fonctionnalités
- Mettre à jour le changelog

### 6. Review Process

1. Critères de validation
- Tests passent
- Code suit les conventions
- Documentation à jour
- Pas de conflits

2. Feedback
- Être constructif
- Expliquer le pourquoi
- Proposer des solutions

### 7. Après la Fusion

- Supprimer la branche locale
- Mettre à jour son fork
- Célébrer la contribution! 🎉
