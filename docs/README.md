# Documentation du Projet Challenge

## Table des matières
1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Architecture](#architecture)
4. [Guide d'utilisation](#guide-dutilisation)
5. [API Reference](#api-reference)
6. [Contribution](#contribution)

## Introduction
Cette application est un système de gestion de challenges et de cohortes avec un système de tirage et de matchs intégré.

## Installation

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Apache/XAMPP
- Composer (optionnel)

### Étapes d'installation
1. Cloner le repository
```bash
git clone [url-du-repo]
```

2. Configurer la base de données
- Créer une base de données MySQL
- Importer le fichier SQL fourni dans `src/database/`
- Configurer les accès dans le fichier de configuration

3. Configurer le serveur web
- Pointer le DocumentRoot vers le dossier du projet
- Activer le module rewrite d'Apache

## Architecture

### Structure du projet
```
challenge/
├── controllers/     # Contrôleurs de l'application
├── models/         # Modèles de données
├── views/          # Vues et templates
├── css/           # Fichiers CSS
├── js/            # Fichiers JavaScript
├── img/           # Images et médias
└── src/           # Code source principal
```

### Composants principaux
- **Controllers**: Gestion de la logique métier
- **Models**: Interaction avec la base de données
- **Views**: Interface utilisateur
