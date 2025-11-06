# Diagramme de Classes UML - Système de Gestion PFE

Ce dossier contient les diagrammes de classes UML complets du système de gestion des Projets de Fin d'Études (PFE).

## 📁 Fichiers Disponibles

### 1. `uml-class-diagram.puml` (PlantUML)
Diagramme complet en format PlantUML avec toutes les classes, relations, énumérations et annotations.

**Comment visualiser :**
- **En ligne** : https://www.plantuml.com/plantuml/uml/
  - Coller le contenu du fichier `.puml`
  - Cliquer sur "Submit"

- **VS Code** : Installer l'extension [PlantUML](https://marketplace.visualstudio.com/items?itemName=jebbs.plantuml)
  - Ouvrir le fichier `.puml`
  - Faire `Alt+D` pour prévisualiser

- **IntelliJ IDEA** : Plugin PlantUML intégration
  - Clic droit sur le fichier → "Show PlantUML Diagram"

- **Ligne de commande** (si PlantUML installé) :
  ```bash
  java -jar plantuml.jar uml-class-diagram.puml
  # Génère un PNG
  ```

### 2. `uml-class-diagram.mmd` (Mermaid)
Version simplifiée en format Mermaid pour une visualisation rapide en ligne.

**Comment visualiser :**
- **En ligne** : https://mermaid.live
  - Copier-coller le contenu du fichier `.mmd`
  - Visualisation instantanée

- **GitHub** : Les fichiers Mermaid sont automatiquement rendus dans les README.md

- **VS Code** : Extension [Markdown Preview Mermaid Support](https://marketplace.visualstudio.com/items?itemName=bierner.markdown-mermaid)

## 🏗️ Architecture du Système

### Classes Principales

#### **User**
Modèle central gérant tous les types d'utilisateurs :
- **Admin** : Gestion complète du système
- **Coordinateur** : Validation des sujets, gestion du département
- **Enseignant** : Proposition de sujets, encadrement des PFE
- **Étudiant** : Demande d'encadrement, réalisation du PFE

**Particularité** : Le matricule est auto-généré pour les étudiants
- Format Licence : `L20250001`, `L20250002`...
- Format Master : `M20250001`, `M20250002`...

#### **Filière**
Représente les filières d'études (Informatique, Mathématiques, etc.)
- Niveau : licence ou master
- Associée à un département
- Regroupe les étudiants et les sujets PFE

#### **SujetPfe**
Sujet de Projet de Fin d'Études proposé par un enseignant
- États : `propose` → `valide` → `archive`
- Validé par un coordinateur
- Associé à une filière et un niveau requis

#### **Pfe**
Le Projet de Fin d'Études en lui-même
- Cycle de vie : `propose` → `en_cours` → `en_attente_soutenance` → `termine`
- Encadré par un enseignant
- Réalisé par 1 à 3 étudiants (groupe)
- Possède une soutenance avec jury et note finale

#### **DemandeEncadrement**
Demande d'encadrement envoyée par un étudiant à un enseignant
- L'étudiant peut choisir un sujet existant ou en proposer un nouveau
- États : `en_attente` → `accepte` / `refuse`

### Relations Importantes

#### Many-to-Many avec Tables Pivot

1. **User ↔ Pfe** (via `etudiants_pfe`)
   - Un PFE peut avoir 1 à 3 étudiants
   - Chaque étudiant a un rôle : `chef` ou `membre`
   - Notes individuelles et appréciations stockées

2. **User ↔ GroupeEtudiants** (via `membres_groupe`)
   - Gestion des groupes d'étudiants
   - Statut de chaque membre dans le groupe

3. **SujetPfe ↔ MotCle** (via `sujet_mot_cle`)
   - Tagging des sujets avec mots-clés
   - Facilite la recherche de sujets

### Énumérations

- **Role** : admin, coordinateur, enseignant, etudiant
- **NiveauFiliere** : licence, master
- **NiveauEtude** : L1, L2, L3, M1, M2
- **StatutSujet** : propose, valide, archive
- **StatutPfe** : propose, en_cours, en_attente_soutenance, termine
- **StatutDemande** : en_attente, accepte, refuse
- **RoleGroupe** : chef, membre
- **RoleJury** : president, rapporteur, examinateur

## 🔄 Flux de Travail Principal

### 1. Proposition de Sujet
```
Enseignant → Propose un SujetPfe
          ↓
Coordinateur → Valide/Rejette le sujet
          ↓
SujetPfe (statut: valide, visible: true)
```

### 2. Demande d'Encadrement
```
Étudiant → Choisit un sujet ou propose le sien
        ↓
Crée une DemandeEncadrement
        ↓
Enseignant → Accepte/Refuse
        ↓
Si accepté → Création du Pfe (statut: en_cours)
```

### 3. Réalisation et Soutenance
```
Pfe (en_cours) → Travail des étudiants
              ↓
Planification soutenance → Pfe (en_attente_soutenance)
              ↓
JurySoutenance → Notes attribuées
              ↓
Pfe (termine) avec note_finale
```

## 📊 Statistiques Clés

- **12 entités principales** (classes métier)
- **3 tables pivot** (relations many-to-many)
- **8 énumérations** (types et statuts)
- **50+ relations** entre les entités

## 🎨 Légende des Couleurs (PlantUML)

- **Vert** (#E8F5E9) : Entités principales
- **Orange** (#FFF3E0) : Énumérations
- **Bleu** (#E3F2FD) : Tables pivot

## 📝 Notes de Conception

### Principes Appliqués

1. **Single Responsibility** : Chaque classe a une responsabilité claire
2. **Soft Delete** : Les données ne sont jamais supprimées physiquement (statut `archive`)
3. **Audit Trail** : Timestamps automatiques sur toutes les tables
4. **Validation Métier** : Logique dans les modèles (ex: `aDejaUnPfeEnCours()`)
5. **Polymorphisme** : Le modèle `User` gère tous les rôles avec des méthodes spécifiques

### Contraintes Métier

- Un étudiant ne peut avoir qu'**un seul PFE en cours** par année universitaire
- Un PFE doit avoir **entre 1 et 3 étudiants**
- Tous les étudiants d'un PFE doivent être de la **même filière**
- Un sujet doit être **validé** avant d'être visible aux étudiants
- Le matricule étudiant est **unique** et **auto-généré**

## 🛠️ Outils Recommandés

### Pour Créer/Éditer
- [PlantUML](https://plantuml.com/) - Diagrammes à partir de texte
- [Draw.io](https://app.diagrams.net/) - Éditeur visuel
- [StarUML](https://staruml.io/) - Outil UML complet

### Pour Visualiser
- [PlantUML Online](https://www.plantuml.com/plantuml/uml/)
- [Mermaid Live](https://mermaid.live)
- [GitHub](https://github.com) - Rendu natif Mermaid

## 📚 Ressources

- [Documentation Laravel Eloquent](https://laravel.com/docs/eloquent)
- [Guide UML](https://www.uml-diagrams.org/class-diagrams-overview.html)
- [PlantUML Guide](https://plantuml.com/fr/guide)
- [Mermaid Documentation](https://mermaid.js.org/syntax/classDiagram.html)

---

**Généré pour le projet** : Système de Gestion PFE - Aissata Elhadj BA
**Date** : Janvier 2025
**Framework** : Laravel 10
**Base de données** : MySQL
